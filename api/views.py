from django.contrib.auth.models import User
from django.utils import timezone
from rest_framework import viewsets, status
from rest_framework.decorators import action
from rest_framework.response import Response
from rest_framework.permissions import IsAuthenticated, IsAdminUser

from taller.models import Cliente, Vehiculo, OrdenTrabajo, Factura
from taller.serializers import ClienteSerializer, VehiculoSerializer, OrdenTrabajoSerializer


class ClienteViewSet(viewsets.ModelViewSet):
    """CRUD completo de clientes. Requiere autenticación."""
    queryset = Cliente.objects.all()
    serializer_class = ClienteSerializer
    permission_classes = [IsAuthenticated]

    @action(detail=False, methods=['post'], permission_classes=[IsAdminUser])
    def convertir_usuario(self, request):
        """
        Solo admin. Crea o vincula un Cliente a partir de un usuario existente.
        Body: { "user_id": 1, "nombre": "...", "telefono": "...", "direccion": "", "email": "opcional" }
        """
        user_id = request.data.get('user_id')
        if not user_id:
            return Response({'error': 'user_id es obligatorio'}, status=status.HTTP_400_BAD_REQUEST)
        try:
            user = User.objects.get(id=user_id)
        except User.DoesNotExist:
            return Response({'error': 'Usuario no encontrado'}, status=status.HTTP_404_NOT_FOUND)

        cliente, created = Cliente.objects.get_or_create(
            user=user,
            defaults={
                'nombre': request.data.get('nombre', user.username),
                'telefono': request.data.get('telefono', ''),
                'email': request.data.get('email', user.email or f'{user.username}@example.com'),
                'direccion': request.data.get('direccion', '')
            }
        )
        if not created:
            for field in ['nombre', 'telefono', 'direccion', 'email']:
                if field in request.data:
                    setattr(cliente, field, request.data[field])
            cliente.save()

        serializer = self.get_serializer(cliente)
        return Response({'detail': 'Cliente creado/vinculado', 'cliente': serializer.data}, status=status.HTTP_200_OK)


class VehiculoViewSet(viewsets.ModelViewSet):
    """CRUD completo de vehículos. Requiere autenticación."""
    queryset = Vehiculo.objects.select_related('cliente').all()
    serializer_class = VehiculoSerializer
    permission_classes = [IsAuthenticated]


# Flujo de estados permitido (solo avanza, nunca retrocede)
FLUJO_ESTADOS = ['recibido', 'en_proceso', 'listo', 'entregado']


class OrdenTrabajoViewSet(viewsets.ModelViewSet):
    """
    CRUD completo de órdenes de trabajo.
    - Cualquier usuario autenticado puede listar, crear y ver detalles.
    - Solo el admin puede cambiar el estado (endpoint: /avanzar_estado/).
    - El estado avanza en orden: recibido → en_proceso → listo → entregado.
    """
    queryset = OrdenTrabajo.objects.select_related('vehiculo').all()
    serializer_class = OrdenTrabajoSerializer
    permission_classes = [IsAuthenticated]

    @action(detail=True, methods=['post'], permission_classes=[IsAdminUser])
    def avanzar_estado(self, request, pk=None):
        """
        Solo admin. Avanza el estado al siguiente en el flujo.
        POST /api/ordenes/{id}/avanzar_estado/
        """
        orden = self.get_object()
        estado_actual = orden.estado

        try:
            indice_actual = FLUJO_ESTADOS.index(estado_actual)
        except ValueError:
            return Response(
                {'error': f'Estado desconocido: {estado_actual}'},
                status=status.HTTP_400_BAD_REQUEST
            )

        if indice_actual >= len(FLUJO_ESTADOS) - 1:
            return Response(
                {'detail': 'La orden ya se encuentra en el estado final: entregado.'},
                status=status.HTTP_400_BAD_REQUEST
            )

        nuevo_estado = FLUJO_ESTADOS[indice_actual + 1]
        orden.estado = nuevo_estado
        if nuevo_estado == 'entregado':
            orden.fecha_entrega = orden.fecha_entrega or timezone.now()
        orden.save()

        if nuevo_estado == 'entregado':
            Factura.objects.get_or_create(
                orden=orden,
                defaults={'total': orden.total}
            )

        serializer = self.get_serializer(orden)
        return Response({
            'detail': f'Estado actualizado: {estado_actual} → {nuevo_estado}',
            'orden': serializer.data
        })

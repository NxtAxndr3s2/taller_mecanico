from rest_framework import viewsets, status
from rest_framework.decorators import action
from rest_framework.response import Response
from rest_framework.permissions import IsAuthenticated, IsAdminUser

from taller.models import Cliente, Vehiculo, OrdenTrabajo
from taller.serializers import ClienteSerializer, VehiculoSerializer, OrdenTrabajoSerializer


class ClienteViewSet(viewsets.ModelViewSet):
    """CRUD completo de clientes. Requiere autenticación."""
    queryset = Cliente.objects.all()
    serializer_class = ClienteSerializer
    permission_classes = [IsAuthenticated]


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
        orden.save()

        serializer = self.get_serializer(orden)
        return Response({
            'detail': f'Estado actualizado: {estado_actual} → {nuevo_estado}',
            'orden': serializer.data
        })

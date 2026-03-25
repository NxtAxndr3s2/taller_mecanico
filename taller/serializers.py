from rest_framework import serializers
from django.contrib.auth.models import User
from .models import Cliente, Vehiculo, OrdenTrabajo, Factura


class ClienteSerializer(serializers.ModelSerializer):
    user = serializers.PrimaryKeyRelatedField(
        queryset=User.objects.all(),
        required=False,
        allow_null=True
    )

    def validate_email(self, value):
        if '@' not in value:
            raise serializers.ValidationError('El email no es válido.')
        return value

    class Meta:
        model = Cliente
        fields = '__all__'


class VehiculoSerializer(serializers.ModelSerializer):

    def validate_placa(self, value):
        if len(value) < 5:
            raise serializers.ValidationError('La placa debe tener al menos 5 caracteres.')
        return value.upper()

    class Meta:
        model = Vehiculo
        fields = '__all__'


class OrdenTrabajoSerializer(serializers.ModelSerializer):
    factura = serializers.SerializerMethodField(read_only=True)

    def validate_descripcion(self, value):
        if len(value) < 10:
            raise serializers.ValidationError('La descripción debe tener al menos 10 caracteres.')
        return value

    class Meta:
        model = OrdenTrabajo
        fields = [
            'id',
            'vehiculo',
            'descripcion',
            'estado',
            'fecha_ingreso',
            'fecha_entrega',
            'total',
            'observaciones',
            'factura',
        ]

    def get_factura(self, obj):
        factura = getattr(obj, 'factura', None)
        if not factura:
            return None
        return FacturaSerializer(factura).data


class FacturaSerializer(serializers.ModelSerializer):
    class Meta:
        model = Factura
        fields = '__all__'

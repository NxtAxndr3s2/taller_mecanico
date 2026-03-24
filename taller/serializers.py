from rest_framework import serializers
from .models import Cliente, Vehiculo, OrdenTrabajo


class ClienteSerializer(serializers.ModelSerializer):

    def validate_email(self, value):
        if not '@' in value:
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

    def validate_descripcion(self, value):
        if len(value) < 10:
            raise serializers.ValidationError('La descripción debe tener al menos 10 caracteres.')
        return value

    class Meta:
        model = OrdenTrabajo
        fields = '__all__'
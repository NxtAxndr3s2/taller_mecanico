from django.contrib import admin
from .models import Cliente, Vehiculo, OrdenTrabajo


@admin.register(Cliente)
class ClienteAdmin(admin.ModelAdmin):
    list_display = ['nombre', 'email', 'telefono']
    search_fields = ['nombre', 'email']


@admin.register(Vehiculo)
class VehiculoAdmin(admin.ModelAdmin):
    list_display = ['placa', 'marca', 'modelo', 'cliente']
    search_fields = ['placa', 'marca']


@admin.register(OrdenTrabajo)
class OrdenTrabajoAdmin(admin.ModelAdmin):
    list_display = ['id', 'vehiculo', 'estado', 'fecha_ingreso']
    list_filter = ['estado']
    search_fields = ['vehiculo__placa']
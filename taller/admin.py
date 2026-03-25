from django.contrib import admin
from .models import Cliente, Vehiculo, OrdenTrabajo, Factura


@admin.register(Cliente)
class ClienteAdmin(admin.ModelAdmin):
    list_display = ['nombre', 'email', 'telefono', 'user']
    search_fields = ['nombre', 'email', 'user__username']


@admin.register(Vehiculo)
class VehiculoAdmin(admin.ModelAdmin):
    list_display = ['placa', 'marca', 'modelo', 'cliente']
    search_fields = ['placa', 'marca']


@admin.register(OrdenTrabajo)
class OrdenTrabajoAdmin(admin.ModelAdmin):
    list_display = ['id', 'vehiculo', 'estado', 'fecha_ingreso']
    list_filter = ['estado']
    search_fields = ['vehiculo__placa']


@admin.register(Factura)
class FacturaAdmin(admin.ModelAdmin):
    list_display = ['id', 'orden', 'total', 'fecha']
    search_fields = ['orden__vehiculo__placa']

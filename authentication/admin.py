from django.contrib import admin
from django.contrib.auth.admin import UserAdmin as BaseUserAdmin
from django.contrib.auth.models import User

from taller.models import Cliente


class CustomUserAdmin(BaseUserAdmin):
    actions = ['convertir_a_cliente']

    def convertir_a_cliente(self, request, queryset):
        creados = 0
        for user in queryset:
            cliente, creado = Cliente.objects.get_or_create(
                user=user,
                defaults={
                    'nombre': user.username,
                    'telefono': '',
                    'email': user.email or f'{user.username}@example.com',
                    'direccion': ''
                }
            )
            if creado:
                creados += 1
        self.message_user(request, f'Clientes creados/vinculados: {creados}')

    convertir_a_cliente.short_description = "Crear/vincular Cliente para usuarios seleccionados"


admin.site.unregister(User)
admin.site.register(User, CustomUserAdmin)

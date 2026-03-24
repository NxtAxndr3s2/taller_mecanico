from django.db import models
from django.contrib.auth.models import User

class Cliente(models.Model):
    user = models.OneToOneField(
        User,
        on_delete=models.CASCADE,
        related_name='cliente_perfil',
        null=True,
        blank=True,
        help_text='Vincula el usuario autenticado con su ficha de cliente'
    )
    nombre = models.CharField(max_length=100)
    telefono = models.CharField(max_length=20)
    email = models.EmailField(unique=True)
    direccion = models.CharField(max_length=200, blank=True)

    def __str__(self):
        return self.nombre

class Vehiculo(models.Model):
    cliente = models.ForeignKey(
        Cliente,
        on_delete=models.CASCADE,
        related_name='vehiculos'
    )
    placa = models.CharField(max_length=10, unique=True)
    marca = models.CharField(max_length=50)
    modelo = models.CharField(max_length=50)
    año = models.IntegerField()
    color = models.CharField(max_length=30, blank=True)
    foto = models.ImageField(
        upload_to='vehiculos/',
        null=True,
        blank=True
    )

    def __str__(self):
        return f'{self.placa} - {self.marca} {self.modelo}'

class OrdenTrabajo(models.Model):
    ESTADOS = [
        ('recibido', 'Recibido'),
        ('en_proceso', 'En Proceso'),
        ('listo', 'Listo'),
        ('entregado', 'Entregado'),
    ]
    vehiculo = models.ForeignKey(Vehiculo, on_delete=models.CASCADE, related_name='ordenes')
    descripcion = models.TextField()
    estado = models.CharField(max_length=20, choices=ESTADOS, default='recibido')
    fecha_ingreso = models.DateTimeField(auto_now_add=True)
    fecha_entrega = models.DateTimeField(null=True, blank=True)
    total = models.DecimalField(max_digits=10, decimal_places=2, default=0)
    observaciones = models.TextField(blank=True)

    def __str__(self):
        return f"Orden #{self.id} - {self.vehiculo.placa} - {self.get_estado_display()}"

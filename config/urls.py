"""
URL configuration for config project.

The `urlpatterns` list routes URLs to views. For more information please see:
    https://docs.djangoproject.com/en/6.0/topics/http/urls/
Examples:
Function views
    1. Add an import:  from my_app import views
    2. Add a URL to urlpatterns:  path('', views.home, name='home')
Class-based views
    1. Add an import:  from other_app.views import Home
    2. Add a URL to urlpatterns:  path('', Home.as_view(), name='home')
Including another URLconf
    1. Import the include() function: from django.urls import include, path
    2. Add a URL to urlpatterns:  path('blog/', include('blog.urls'))
"""
from django.contrib import admin
from django.urls import path, include
from django.conf import settings
from django.conf.urls.static import static
from rest_framework.routers import DefaultRouter
from django.views.generic import TemplateView


from api.views import ClienteViewSet, VehiculoViewSet, OrdenTrabajoViewSet
from authentication.views import registro, login, logout

router = DefaultRouter()
router.register(r'clientes', ClienteViewSet, basename='cliente')
router.register(r'vehiculos', VehiculoViewSet, basename='vehiculo')
router.register(r'ordenes', OrdenTrabajoViewSet, basename='orden')


urlpatterns = [
    path('admin/', admin.site.urls),
    path('api/', include(router.urls)),
    path('auth/registro/', registro, name='registro'),
    path('auth/login/', login, name='login'),
    path('auth/logout/', logout, name='logout'),
    path('clientes/', TemplateView.as_view(template_name='clientes.html'), name='clientes'),
    path('vehiculos/', TemplateView.as_view(template_name='vehiculos.html'), name='vehiculos'),
    path('ordenes/', TemplateView.as_view(template_name='ordenes.html'), name='ordenes'),
    path('', TemplateView.as_view(template_name='index.html'), name='index'),

] + static(settings.MEDIA_URL, document_root=settings.MEDIA_ROOT)

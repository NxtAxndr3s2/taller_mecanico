# Taller Mecánico – API y Frontend

Proyecto de ejemplo para un taller automotriz con Django 4.2 + Django REST Framework. Cumple con los requisitos del curso:

- 3 modelos relacionados (`Cliente`, `Vehiculo`, `OrdenTrabajo`).
- API REST con endpoints GET/POST/PUT/PATCH/DELETE (via `ModelViewSet`).
- Autenticación por token: registro, login y logout funcionando.
- Endpoint protegido (todas las vistas requieren `TokenAuthentication`).
- Validaciones personalizadas en serializers (`validate_email`, `validate_placa`, `validate_descripcion`).
- Base de datos PostgreSQL (Supabase) vía `DATABASE_URL`.
- Frontend con 3 vistas HTML usando Bootstrap 5 (`clientes`, `vehiculos`, `ordenes`).

## Requisitos previos
- Python 3.10+ (probado con 3.13).
- PostgreSQL en Supabase y una URL de conexión válida.
- Git.

## Variables de entorno (`.env`)
> **No subas este archivo a GitHub.**
```
SECRET_KEY=pon-una-clave-segura
DEBUG=False
DATABASE_URL=postgresql://usuario:password@host:puerto/base
ALLOWED_HOSTS=localhost,127.0.0.1
```

## Instalación
```bash
python -m venv venv
source venv/bin/activate   # En Windows: venv\Scripts\activate
pip install -r requirements.txt
python manage.py migrate
python manage.py createsuperuser
python manage.py runserver
```

## Endpoints principales
- `/api/clientes/`
- `/api/vehiculos/`
- `/api/ordenes/`
- `/api/ordenes/<id>/avanzar_estado/` (solo admin)
- `/auth/registro/`, `/auth/login/`, `/auth/logout/`

Enviar `Authorization: Token <token>` en los endpoints protegidos.

## Frontend (Bootstrap)
- `/` – página de inicio.
- `/clientes/` – listado/CTA clientes.
- `/vehiculos/` – ficha de vehículos/órdenes.

## Cómo subir a GitHub (pasos rápidos)
```bash
cd C:\Users\Andres Felipe\Downloads\taller_mecanico
git init
git add .
git commit -m "Proyecto taller mecanico inicial"
git branch -M main
git remote add origin https://github.com/<tu-usuario>/<repo>.git
git push -u origin main
```
Asegúrate de que `.env` está en `.gitignore` antes de `git add .`.

## Notas de seguridad
- Rotar cualquier credencial expuesta antes de publicar.
- Servir `MEDIA` y `STATIC` con un servidor web en producción (no con Django).

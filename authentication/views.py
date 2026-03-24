from django.contrib.auth.models import User
from rest_framework import status
from rest_framework.authtoken.models import Token
from rest_framework.decorators import api_view, permission_classes
from rest_framework.permissions import AllowAny, IsAuthenticated
from rest_framework.response import Response
from taller.models import Cliente


@api_view(['POST'])
@permission_classes([AllowAny])
def registro(request):
    """
    Registro de nuevo usuario.
    POST /auth/registro/
    Body: { "username": "...", "password": "...", "email": "..." }
    """
    username = request.data.get('username')
    password = request.data.get('password')
    email = request.data.get('email', '')

    if not username or not password:
        return Response(
            {'error': 'username y password son obligatorios.'},
            status=status.HTTP_400_BAD_REQUEST
        )

    if User.objects.filter(username=username).exists():
        return Response(
            {'error': 'El nombre de usuario ya está en uso.'},
            status=status.HTTP_400_BAD_REQUEST
        )

    user = User.objects.create_user(username=username, password=password, email=email)
    token, _ = Token.objects.get_or_create(user=user)

    # Crear ficha de cliente automáticamente para el usuario registrado
    cliente_email = email or f"{username}@example.com"
    Cliente.objects.get_or_create(
        user=user,
        defaults={
            'nombre': username,
            'telefono': '',
            'email': cliente_email,
            'direccion': ''
        }
    )

    return Response(
        {
            'token': token.key,
            'user_id': user.id,
            'username': user.username,
            'email': user.email,
            'is_admin': user.is_staff,
        },
        status=status.HTTP_201_CREATED
    )


@api_view(['POST'])
@permission_classes([AllowAny])
def login(request):
    """
    Login de usuario existente.
    POST /auth/login/
    Body: { "username": "...", "password": "..." }
    """
    from django.contrib.auth import authenticate

    username = request.data.get('username')
    password = request.data.get('password')

    if not username or not password:
        return Response(
            {'error': 'username y password son obligatorios.'},
            status=status.HTTP_400_BAD_REQUEST
        )

    user = authenticate(username=username, password=password)

    if user is None:
        return Response(
            {'error': 'Credenciales incorrectas.'},
            status=status.HTTP_401_UNAUTHORIZED
        )

    token, _ = Token.objects.get_or_create(user=user)

    return Response({
        'token': token.key,
        'user_id': user.id,
        'username': user.username,
        'email': user.email,
        'is_admin': user.is_staff,
    })


@api_view(['POST'])
@permission_classes([IsAuthenticated])
def logout(request):
    """
    Logout: elimina el token del usuario actual.
    POST /auth/logout/
    Header: Authorization: Token <token>
    """
    request.user.auth_token.delete()
    return Response({'detail': 'Sesión cerrada correctamente.'}, status=status.HTTP_200_OK)

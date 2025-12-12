<?php
declare(strict_types=1);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require __DIR__ . '/../app/helpers.php';
date_default_timezone_set((string)config('app.timezone', 'America/Bogota'));

// Autoload simple
spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/../app/Core/' . $class . '.php',
        __DIR__ . '/../app/Controllers/' . $class . '.php',
        __DIR__ . '/../app/Models/' . $class . '.php',
    ];
    foreach ($paths as $p) {
        if (file_exists($p)) { require $p; return; }
    }
});

$router = new Router();

/* HOME */
$router->get('/', [ClientesController::class, 'index']);

/* CLIENTES (solo rutas simples por ahora) */
$router->get('/clientes', [ClientesController::class, 'index']);
$router->get('/clientes/create', [ClientesController::class, 'create']);
$router->post('/clientes', [ClientesController::class, 'store']);
$router->get('/clientes/{id}/edit', [ClientesController::class, 'edit']);
$router->post('/clientes/{id}/update', [ClientesController::class, 'update']);
$router->post('/clientes/{id}/delete', [ClientesController::class, 'delete']);

// VEHICULOS
$router->get('/vehiculos', [VehiculosController::class, 'index']);
$router->get('/vehiculos/create', [VehiculosController::class, 'create']);
$router->post('/vehiculos', [VehiculosController::class, 'store']);
$router->get('/vehiculos/{id}/edit', [VehiculosController::class, 'edit']);
$router->post('/vehiculos/{id}/update', [VehiculosController::class, 'update']);
$router->post('/vehiculos/{id}/delete', [VehiculosController::class, 'delete']);

// ORDENES
$router->get('/ordenes', [OrdenesController::class, 'index']);
$router->get('/ordenes/create', [OrdenesController::class, 'create']);
$router->post('/ordenes', [OrdenesController::class, 'store']);
$router->get('/ordenes/{id}/edit', [OrdenesController::class, 'edit']);
$router->post('/ordenes/{id}/update', [OrdenesController::class, 'update']);
$router->post('/ordenes/{id}/delete', [OrdenesController::class, 'delete']);



$router->get('/ordenes/{id}', [OrdenesController::class, 'show']);
$router->post('/ordenes/{id}/repuestos', [OrdenesController::class, 'addRepuesto']);
$router->post('/ordenes/{id}/repuestos/{detalle}/delete', [OrdenesController::class, 'removeRepuesto']);

// REPUESTOS
$router->get('/repuestos', [RepuestosController::class, 'index']);
$router->get('/repuestos/create', [RepuestosController::class, 'create']);
$router->post('/repuestos', [RepuestosController::class, 'store']);
$router->get('/repuestos/{id}/edit', [RepuestosController::class, 'edit']);
$router->post('/repuestos/{id}/update', [RepuestosController::class, 'update']);
$router->post('/repuestos/{id}/delete', [RepuestosController::class, 'delete']);

$router->get('/ordenes/{id}', [OrdenesController::class, 'show']);
$router->post('/ordenes/{id}/repuestos', [OrdenesController::class, 'addRepuesto']);
$router->post('/ordenes/{id}/repuestos/{detalle}/delete', [OrdenesController::class, 'removeRepuesto']);


$router->get('/', [AuthController::class, 'landing']);

$router->get('/login', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'authenticate']);
$router->post('/logout', [AuthController::class, 'logout']);

$router->get('/dashboard', [DashboardController::class, 'index']);

$router->get('/login', [AuthController::class, 'loginForm']);
$router->post('/login', [AuthController::class, 'login']);
$router->post('/logout', [AuthController::class, 'logout']);

$router->get('/dashboard', [DashboardController::class, 'index']);

// Cambiar estado de una orden
$router->post('/ordenes/{id}/estado', [OrdenesController::class, 'changeEstado']);




/* NOTA:
   Tu Router actual NO soporta {id}. Cuando confirmemos todo,
   te paso el Router con {id} para edit/update/delete.
*/

// DISPATCH (UNA SOLA VEZ Y AL FINAL)
$method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];
$router->dispatch($method, $_SERVER['REQUEST_URI']);

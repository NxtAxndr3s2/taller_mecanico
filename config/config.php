<?php

return [
  'app' => [
   'base_url' => '/taller_mecanico/public',

    'name' => 'Taller Mecánico',
    'timezone' => 'America/Bogota',
  ],
  'db' => [
    'host' => 'localhost',
    'name' => 'taller_mecanico',
    'user' => 'root',
    'pass' => '',
    'charset' => 'utf8mb4',
  ],
  'auth' => [
    // demo admin (cámbialo si quieres)
    'username' => 'admin',
    'name' => 'Admin',
    // password: admin123
    'password_hash' => password_hash('admin123', PASSWORD_DEFAULT),
  ],
];
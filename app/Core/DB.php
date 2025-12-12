<?php

class DB
{
  private static ?PDO $pdo = null;

  public static function pdo(): PDO
  {
    if (self::$pdo) return self::$pdo;

    $db = config('db');
    if (!is_array($db)) {
      die('Config DB no cargó. Revisa config/config.php (debe tener <?php y return array).');
    }

    $dsn = "mysql:host={$db['host']};dbname={$db['name']};charset={$db['charset']}";

    try {
      self::$pdo = new PDO($dsn, $db['user'], $db['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      ]);
    } catch (PDOException $e) {
      die("Error de BD: " . $e->getMessage());
    }

    return self::$pdo;
  }
}

<?php

class Vehiculo
{
    public static function all(): array
    {
        $pdo = DB::pdo();
        $sql = "SELECT v.*, c.nombre AS cliente_nombre
                FROM Vehiculo v
                INNER JOIN Cliente c ON c.id_cliente = v.id_cliente
                ORDER BY v.id_vehiculo DESC";
        return $pdo->query($sql)->fetchAll();
    }

  public static function options(): array
{
    $pdo = DB::pdo();
    $sql = "SELECT v.id_vehiculo, v.placa, c.nombre AS cliente_nombre
            FROM Vehiculo v
            INNER JOIN Cliente c ON c.id_cliente = v.id_cliente
            ORDER BY c.nombre, v.placa";
    return $pdo->query($sql)->fetchAll();
}


    public static function find(int $id): ?array
    {
        $pdo = DB::pdo();
        $st = $pdo->prepare("SELECT * FROM Vehiculo WHERE id_vehiculo = ?");
        $st->execute([$id]);
        $row = $st->fetch();
        return $row ?: null;
    }

    public static function create(array $data): void
    {
        $pdo = DB::pdo();
        $st = $pdo->prepare(
            "INSERT INTO Vehiculo (id_cliente, placa, marca, modelo, año)
             VALUES (?, ?, ?, ?, ?)"
        );
        $st->execute([
            $data['id_cliente'],
            $data['placa'],
            $data['marca'],
            $data['modelo'],
            $data['año'],
        ]);
    }

    public static function update(int $id, array $data): void
    {
        $pdo = DB::pdo();
        $st = $pdo->prepare(
            "UPDATE Vehiculo
             SET id_cliente = ?, placa = ?, marca = ?, modelo = ?, año  = ?
             WHERE id_vehiculo = ?"
        );
        $st->execute([
            $data['id_cliente'],
            $data['placa'],
            $data['marca'],
            $data['modelo'],
            $data['año'],
            $id
        ]);
    }

    public static function delete(int $id): void
    {
        $pdo = DB::pdo();
        $st = $pdo->prepare("DELETE FROM Vehiculo WHERE id_vehiculo = ?");
        $st->execute([$id]);
    }
}

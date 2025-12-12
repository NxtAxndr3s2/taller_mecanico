<?php

class Cliente
{
    public static function all(): array
    {
        $pdo = DB::pdo();
        return $pdo
            ->query("SELECT * FROM Cliente ORDER BY id_cliente DESC")
            ->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $pdo = DB::pdo();
        $st = $pdo->prepare(
            "SELECT * FROM Cliente WHERE id_cliente = ?"
        );
        $st->execute([$id]);
        $row = $st->fetch();
        return $row ?: null;
    }

    public static function create(array $data): void
    {
        $pdo = DB::pdo();
        $st = $pdo->prepare(
            "INSERT INTO Cliente (nombre, telefono, email, direccion)
             VALUES (?, ?, ?, ?)"
        );
        $st->execute([
            $data['nombre'],
            $data['telefono'] ?? null,
            $data['email'] ?? null,
            $data['direccion'] ?? null,
        ]);
    }

    public static function update(int $id, array $data): void
    {
        $pdo = DB::pdo();
        $st = $pdo->prepare(
            "UPDATE Cliente
             SET nombre=?, telefono=?, email=?, direccion=?
             WHERE id_cliente=?"
        );
        $st->execute([
            $data['nombre'],
            $data['telefono'] ?? null,
            $data['email'] ?? null,
            $data['direccion'] ?? null,
            $id
        ]);
    }

    public static function delete(int $id): void
    {
        $pdo = DB::pdo();
        $st = $pdo->prepare(
            "DELETE FROM Cliente WHERE id_cliente = ?"
        );
        $st->execute([$id]);
    }
}

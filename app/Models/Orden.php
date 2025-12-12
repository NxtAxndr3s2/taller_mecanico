<?php
class Orden
{
    public static function all(): array
    {
        $pdo = DB::pdo();
        $sql = "SELECT o.*,
                       v.placa AS vehiculo_placa,
                       c.nombre AS cliente_nombre
                FROM Orden o
                INNER JOIN Vehiculo v ON v.id_vehiculo = o.id_vehiculo
                INNER JOIN Cliente c ON c.id_cliente = v.id_cliente
                ORDER BY o.id_orden DESC";
        return $pdo->query($sql)->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $pdo = DB::pdo();
        $st = $pdo->prepare("SELECT * FROM Orden WHERE id_orden = ?");
        $st->execute([$id]);
        $row = $st->fetch();
        return $row ?: null;
    }

    public static function create(array $data): void
    {
        $pdo = DB::pdo();
        $st = $pdo->prepare("INSERT INTO Orden (id_vehiculo, fecha, estado, total)
                             VALUES (?, ?, ?, ?)");
        $st->execute([
            (int)$data['id_vehiculo'],
            $data['fecha'],
            $data['estado'],
            (float)($data['total'] ?? 0),
        ]);
    }

    public static function update(int $id, array $data): void
    {
        $pdo = DB::pdo();
        $st = $pdo->prepare("UPDATE Orden
                             SET id_vehiculo=?, fecha=?, estado=?, total=?
                             WHERE id_orden=?");
        $st->execute([
            (int)$data['id_vehiculo'],
            $data['fecha'],
            $data['estado'],
            (float)($data['total'] ?? 0),
            $id
        ]);
    }

    public static function delete(int $id): void
    {
        $pdo = DB::pdo();
        $st = $pdo->prepare("DELETE FROM Orden WHERE id_orden = ?");
        $st->execute([$id]);
    }
    public static function updateTotal(int $idOrden, float $total): void
{
    $pdo = DB::pdo();
    $st = $pdo->prepare("UPDATE Orden SET total = ? WHERE id_orden = ?");
    $st->execute([$total, $idOrden]);
}
public static function updateEstado(int $id, string $estado): void
{
    $pdo = DB::pdo();
    $st = $pdo->prepare("UPDATE Orden SET estado = ? WHERE id_orden = ?");
    $st->execute([$estado, $id]);
}

}   

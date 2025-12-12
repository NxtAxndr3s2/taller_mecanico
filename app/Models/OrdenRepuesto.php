<?php
class OrdenRepuesto
{
    public static function byOrden(int $idOrden): array
    {
        $pdo = DB::pdo();
        $sql = "SELECT orp.id_orden_repuesto, orp.id_orden, orp.id_repuesto,
                       orp.cantidad, orp.precio_unitario,
                       r.nombre AS repuesto_nombre
                FROM Orden_Repuesto orp
                INNER JOIN Repuesto r ON r.id_repuesto = orp.id_repuesto
                WHERE orp.id_orden = ?
                ORDER BY orp.id_orden_repuesto DESC";
        $st = $pdo->prepare($sql);
        $st->execute([$idOrden]);
        return $st->fetchAll();
    }

    public static function add(int $idOrden, int $idRepuesto, int $cantidad, float $precioUnit): void
    {
        $pdo = DB::pdo();
        $st = $pdo->prepare("INSERT INTO Orden_Repuesto (id_orden, id_repuesto, cantidad, precio_unitario)
                             VALUES (?, ?, ?, ?)");
        $st->execute([$idOrden, $idRepuesto, $cantidad, $precioUnit]);
    }

    public static function find(int $idDetalle): ?array
    {
        $pdo = DB::pdo();
        $st = $pdo->prepare("SELECT * FROM Orden_Repuesto WHERE id_orden_repuesto = ?");
        $st->execute([$idDetalle]);
        $row = $st->fetch();
        return $row ?: null;
    }

    public static function delete(int $idDetalle): void
    {
        $pdo = DB::pdo();
        $st = $pdo->prepare("DELETE FROM Orden_Repuesto WHERE id_orden_repuesto = ?");
        $st->execute([$idDetalle]);
    }

    public static function totalOrden(int $idOrden): float
    {
        $pdo = DB::pdo();
        $st = $pdo->prepare("SELECT COALESCE(SUM(cantidad * precio_unitario), 0) AS total
                             FROM Orden_Repuesto WHERE id_orden = ?");
        $st->execute([$idOrden]);
        $row = $st->fetch();
        return (float)($row['total'] ?? 0);
    }
}

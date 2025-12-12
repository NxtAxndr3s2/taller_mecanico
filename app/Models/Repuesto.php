<?php
class Repuesto
{
    /* =========================
       LISTAR TODOS
       ========================= */
    public static function all(): array
    {
        $pdo = DB::pdo();
        return $pdo->query(
            "SELECT * FROM Repuesto ORDER BY nombre"
        )->fetchAll();
    }

    /* =========================
       SELECT PARA COMBOS
       ========================= */
    public static function options(): array
    {
        return self::all();
    }

    public static function find(int $id): ?array
    {
        $pdo = DB::pdo();
        $st = $pdo->prepare("SELECT * FROM Repuesto WHERE id_repuesto = ?");
        $st->execute([$id]);
        $row = $st->fetch();
        return $row ?: null;
    }

    /* 🔒 Bloqueo para transacciones */
    public static function findForUpdate(int $id): ?array
    {
        $pdo = DB::pdo();
        $st = $pdo->prepare(
            "SELECT * FROM Repuesto WHERE id_repuesto = ? FOR UPDATE"
        );
        $st->execute([$id]);
        $row = $st->fetch();
        return $row ?: null;
    }

    public static function create(array $data): void
    {
        $pdo = DB::pdo();
        $st = $pdo->prepare(
            "INSERT INTO Repuesto (nombre, stock, precio)
             VALUES (?, ?, ?)"
        );
        $st->execute([
            $data['nombre'],
            $data['stock'],
            $data['precio']
        ]);
    }

    public static function update(int $id, array $data): void
    {
        $pdo = DB::pdo();
        $st = $pdo->prepare(
            "UPDATE Repuesto
             SET nombre = ?, stock = ?, precio = ?
             WHERE id_repuesto = ?"
        );
        $st->execute([
            $data['nombre'],
            $data['stock'],
            $data['precio'],
            $id
        ]);
    }

    public static function delete(int $id): void
    {
        $pdo = DB::pdo();
        $st = $pdo->prepare(
            "DELETE FROM Repuesto WHERE id_repuesto = ?"
        );
        $st->execute([$id]);
    }

    /* =========================
       STOCK
       ========================= */
public static function decreaseStock(int $id, int $cantidad): bool
{
    $pdo = DB::pdo();
    $st = $pdo->prepare(
        "UPDATE Repuesto
         SET stock = stock - ?
         WHERE id_repuesto = ? AND stock >= ?"
    );
    $st->execute([$cantidad, $id, $cantidad]);
    return $st->rowCount() > 0; // true si logró descontar
}


    public static function increaseStock(int $id, int $cantidad): void
    {
        $pdo = DB::pdo();
        $st = $pdo->prepare(
            "UPDATE Repuesto
             SET stock = stock + ?
             WHERE id_repuesto = ?"
        );
        $st->execute([$cantidad, $id]);
    }
}

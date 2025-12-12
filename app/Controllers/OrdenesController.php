<?php
class OrdenesController extends Controller
{
    public function index(): void
    {
        $ordenes = Orden::all();
        $this->view('ordenes/index', compact('ordenes'));
    }

    public function create(): void
    {
        $vehiculos = Vehiculo::options();

        $this->view('ordenes/form', [
            'orden' => [
                'id_vehiculo' => '',
                'fecha' => date('Y-m-d'),
                'estado' => 'abierta',
                'total' => 0
            ],
            'vehiculos' => $vehiculos,
            'mode' => 'create'
        ]);
    }

    public function store(): void
    {
        $id_vehiculo = (int)($_POST['id_vehiculo'] ?? 0);
        $fecha = $_POST['fecha'] ?? date('Y-m-d');
        $estado = $_POST['estado'] ?? 'abierta';

        if ($id_vehiculo <= 0) {
            flash_set('error', 'Selecciona un vehículo.');
            redirect('/ordenes/create');
        }

        Orden::create([
            'id_vehiculo' => $id_vehiculo,
            'fecha' => $fecha,
            'estado' => $estado,
            'total' => 0,
        ]);

        flash_set('success', 'Orden creada correctamente.');
        redirect('/ordenes');
    }

    public function edit(int $id): void
    {
        $orden = Orden::find($id);
        if (!$orden) {
            flash_set('error', 'Orden no existe.');
            redirect('/ordenes');
        }

        $vehiculos = Vehiculo::options();

        $this->view('ordenes/form', [
            'orden' => $orden,
            'vehiculos' => $vehiculos,
            'mode' => 'edit'
        ]);
    }

    public function update(int $id): void
    {
        $id_vehiculo = (int)($_POST['id_vehiculo'] ?? 0);
        $fecha = $_POST['fecha'] ?? date('Y-m-d');
        $estado = $_POST['estado'] ?? 'abierta';

        if ($id_vehiculo <= 0) {
            flash_set('error', 'Selecciona un vehículo.');
            redirect("/ordenes/$id/edit");
        }

        Orden::update($id, [
            'id_vehiculo' => $id_vehiculo,
            'fecha' => $fecha,
            'estado' => $estado,
        ]);

        flash_set('success', 'Orden actualizada.');
        redirect('/ordenes');
    }

  public function delete(int $id): void
{
    // 🔎 Verificar si la orden tiene repuestos asignados
    $detalles = OrdenRepuesto::byOrden($id);

    if (!empty($detalles)) {
        flash_set(
            'error',
            'No se puede eliminar la orden porque tiene repuestos asignados. Primero quítalos.'
        );
        redirect('/ordenes');
    }

    // ✅ Si no tiene repuestos, se elimina sin problema
    Orden::delete($id);
    flash_set('success', 'Orden eliminada correctamente.');
    redirect('/ordenes');
}


    /* =========================
       DETALLE + REPUESTOS
       ========================= */

    public function show(int $id): void
    {
        $pdo = DB::pdo();
        $sql = "SELECT o.*, v.placa AS vehiculo_placa, c.nombre AS cliente_nombre
                FROM Orden o
                JOIN Vehiculo v ON v.id_vehiculo = o.id_vehiculo
                JOIN Cliente c ON c.id_cliente = v.id_cliente
                WHERE o.id_orden = ?";
        $st = $pdo->prepare($sql);
        $st->execute([$id]);
        $orden = $st->fetch();

        if (!$orden) {
            flash_set('error', 'Orden no existe.');
            redirect('/ordenes');
        }

        $detalles = OrdenRepuesto::byOrden($id);
        $repuestos = Repuesto::options();

        $this->view('ordenes/show', compact('orden', 'detalles', 'repuestos'));
    }

    public function addRepuesto(int $id): void
    {
        $idRepuesto = (int)($_POST['id_repuesto'] ?? 0);
        $cantidad   = (int)($_POST['cantidad'] ?? 0);

        if ($idRepuesto <= 0 || $cantidad <= 0) {
            flash_set('error', 'Datos inválidos.');
            redirect("/ordenes/$id");
        }

        $pdo = DB::pdo();

        try {
            $pdo->beginTransaction();

            $rep = Repuesto::findForUpdate($idRepuesto);
            if (!$rep) {
                $pdo->rollBack();
                flash_set('error', 'Repuesto no existe.');
                redirect("/ordenes/$id");
            }

            if (!Repuesto::decreaseStock($idRepuesto, $cantidad)) {
                $pdo->rollBack();
                flash_set('error', 'No hay stock suficiente.');
                redirect("/ordenes/$id");
            }

            OrdenRepuesto::add(
                $id,
                $idRepuesto,
                $cantidad,
                (float)$rep['precio']
            );

            Orden::updateTotal($id, OrdenRepuesto::totalOrden($id));

            $pdo->commit();
            flash_set('success', 'Repuesto agregado.');
            redirect("/ordenes/$id");

        } catch (Throwable $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            flash_set('error', 'Error agregando repuesto.');
            redirect("/ordenes/$id");
        }
    }

    public function removeRepuesto(int $id, int $detalle): void
    {
        $pdo = DB::pdo();

        try {
            $pdo->beginTransaction();

            $row = OrdenRepuesto::find($detalle);
            if (!$row) {
                $pdo->rollBack();
                flash_set('error', 'Detalle no encontrado.');
                redirect("/ordenes/$id");
            }

            Repuesto::increaseStock(
                (int)$row['id_repuesto'],
                (int)$row['cantidad']
            );

            OrdenRepuesto::delete($detalle);
            Orden::updateTotal($id, OrdenRepuesto::totalOrden($id));

            $pdo->commit();
            flash_set('success', 'Repuesto eliminado.');
            redirect("/ordenes/$id");

        } catch (Throwable $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            flash_set('error', 'Error quitando repuesto.');
            redirect("/ordenes/$id");
        }
    }
 public function changeEstado(int $id): void
{
    $estado = (string)($_POST['estado'] ?? '');
    $validos = ['abierta', 'en_proceso', 'cerrada'];

    if (!in_array($estado, $validos, true)) {
        flash_set('error', 'Estado inválido.');
        redirect("/ordenes/$id");
    }

    Orden::updateEstado($id, $estado);

    flash_set('success', 'Estado actualizado.');
    redirect("/ordenes/$id");
}

}


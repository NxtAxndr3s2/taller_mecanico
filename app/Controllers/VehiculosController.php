<?php
class VehiculosController extends Controller
{
    public function index(): void
    {
        $vehiculos = Vehiculo::all();
        $this->view('vehiculos/index', compact('vehiculos'));
    }

    public function create(): void
    {
        $clientes = Cliente::all();
        $this->view('vehiculos/form', [
            'mode' => 'create',
            'vehiculo' => ['id_cliente'=>'','placa'=>'','marca'=>'','modelo'=>'','año'=>''],
            'clientes' => $clientes,
        ]);
    }

public function store(): void
{
    $id_cliente = (int)($_POST['id_cliente'] ?? 0);
    $placa  = strtoupper(trim($_POST['placa'] ?? ''));
    $marca  = trim($_POST['marca'] ?? '');
    $modelo = trim($_POST['modelo'] ?? '');
    $anio   = (int)($_POST['anio'] ?? 0);

    // ✅ validaciones
    if ($id_cliente <= 0) {
        flash_set('error', 'Debes seleccionar un cliente.');
        redirect('/vehiculos/create');
    }
    if ($placa === '') {
        flash_set('error', 'La placa es obligatoria.');
        redirect('/vehiculos/create');
    }

    try {
        Vehiculo::create([
            'id_cliente' => $id_cliente,
            'placa' => $placa,
            'marca' => $marca,
            'modelo' => $modelo,
            'anio' => $anio ?: null,
        ]);

        flash_set('success', 'Vehículo creado correctamente.');
        redirect('/vehiculos');
    } catch (Throwable $e) {
        // ✅ si es placa repetida (Unique)
        if (str_contains($e->getMessage(), 'Duplicate') || str_contains($e->getMessage(), '1062')) {
            flash_set('error', 'Esa placa ya existe. Usa otra.');
        } else {
            flash_set('error', 'Error al guardar el vehículo: ' . $e->getMessage());
        }
        redirect('/vehiculos/create');
    }
}


    public function edit(int $id): void
    {
        $vehiculo = Vehiculo::find($id);
        if (!$vehiculo) {
            flash_set('error', 'Vehículo no existe.');
            redirect('/vehiculos');
        }

        $clientes = Cliente::all();
        $this->view('vehiculos/form', [
            'mode' => 'edit',
            'vehiculo' => $vehiculo,
            'clientes' => $clientes,
        ]);
    }

    public function update(int $id): void
    {
        $id_cliente = (int)($_POST['id_cliente'] ?? 0);
        $placa = strtoupper(trim($_POST['placa'] ?? ''));

        if ($id_cliente <= 0) {
            flash_set('error', 'Selecciona un cliente.');
            redirect("/vehiculos/$id/edit");
        }
        if ($placa === '') {
            flash_set('error', 'La placa es obligatoria.');
            redirect("/vehiculos/$id/edit");
        }

        try {
            Vehiculo::update($id, [
                'id_cliente' => $id_cliente,
                'placa' => $placa,
                'marca' => trim($_POST['marca'] ?? ''),
                'modelo' => trim($_POST['modelo'] ?? ''),
                'año' => trim($_POST['año'] ?? ''),
            ]);
        } catch (PDOException $e) {
            flash_set('error', 'Error al actualizar. ¿Placa repetida?');
            redirect("/vehiculos/$id/edit");
        }

        flash_set('success', 'Vehículo actualizado.');
        redirect('/vehiculos');
    }

    public function delete(int $id): void
    {
        Vehiculo::delete($id);
        flash_set('success', 'Vehículo eliminado.');
        redirect('/vehiculos');
    }
}

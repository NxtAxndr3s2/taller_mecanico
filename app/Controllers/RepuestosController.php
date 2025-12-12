<?php
class RepuestosController extends Controller
{
    public function index(): void
    {
        $repuestos = Repuesto::all();
        $this->view('repuestos/index', compact('repuestos'));
    }

    public function create(): void
    {
        $this->view('repuestos/form', [
            'repuesto' => ['nombre'=>'', 'stock'=>0, 'precio'=>0],
            'mode' => 'create'
        ]);
    }

    public function store(): void
    {
        $nombre = trim($_POST['nombre'] ?? '');
        $stock  = (int)($_POST['stock'] ?? 0);
        $precio = (float)($_POST['precio'] ?? 0);

        if ($nombre === '') {
            flash_set('error', 'El nombre es obligatorio.');
            redirect('/repuestos/create');
        }
        if ($precio <= 0) {
            flash_set('error', 'El precio debe ser mayor a 0.');
            redirect('/repuestos/create');
        }
        if ($stock < 0) $stock = 0;

        Repuesto::create([
            'nombre' => $nombre,
            'stock'  => $stock,
            'precio' => $precio,
        ]);

        flash_set('success', 'Repuesto creado correctamente.');
        redirect('/repuestos');
    }

    public function edit(int $id): void
    {
        $repuesto = Repuesto::find($id);
        if (!$repuesto) {
            flash_set('error', 'Repuesto no existe.');
            redirect('/repuestos');
        }
        $this->view('repuestos/form', [
            'repuesto' => $repuesto,
            'mode' => 'edit'
        ]);
    }

    public function update(int $id): void
    {
        $nombre = trim($_POST['nombre'] ?? '');
        $stock  = (int)($_POST['stock'] ?? 0);
        $precio = (float)($_POST['precio'] ?? 0);

        if ($nombre === '') {
            flash_set('error', 'El nombre es obligatorio.');
            redirect("/repuestos/$id/edit");
        }
        if ($precio <= 0) {
            flash_set('error', 'El precio debe ser mayor a 0.');
            redirect("/repuestos/$id/edit");
        }
        if ($stock < 0) $stock = 0;

        Repuesto::update($id, [
            'nombre' => $nombre,
            'stock'  => $stock,
            'precio' => $precio,
        ]);

        flash_set('success', 'Repuesto actualizado.');
        redirect('/repuestos');
    }

    public function delete(int $id): void
    {
        Repuesto::delete($id);
        flash_set('success', 'Repuesto eliminado.');
        redirect('/repuestos');
    }
}

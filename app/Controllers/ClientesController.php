<?php

class ClientesController extends Controller
{
    public function index(): void
    {
        $clientes = Cliente::all();
        $this->view('clientes/index', compact('clientes'));
    }

    public function create(): void
    {
        $this->view('clientes/form', [
            'cliente' => ['nombre'=>'','telefono'=>'','email'=>'','direccion'=>''],
            'mode' => 'create'
        ]);
    }

    public function store(): void
    {
        $nombre = trim($_POST['nombre'] ?? '');
        if ($nombre === '') {
            flash_set('error', 'El nombre es obligatorio.');
            redirect('/clientes/create');
        }

        Cliente::create([
            'nombre' => $nombre,
            'telefono' => trim($_POST['telefono'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'direccion' => trim($_POST['direccion'] ?? ''),
        ]);

        flash_set('success', 'Cliente creado correctamente.');
        redirect('/clientes');
    }

    public function edit(int $id): void
    {
        $cliente = Cliente::find($id);
        if (!$cliente) {
            flash_set('error', 'Cliente no existe.');
            redirect('/clientes');
        }

        $this->view('clientes/form', [
            'cliente' => $cliente,
            'mode' => 'edit'
        ]);
    }

    public function update(int $id): void
    {
        $nombre = trim($_POST['nombre'] ?? '');
        if ($nombre === '') {
            flash_set('error', 'El nombre es obligatorio.');
            redirect("/clientes/$id/edit");
        }

        Cliente::update($id, [
            'nombre' => $nombre,
            'telefono' => trim($_POST['telefono'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'direccion' => trim($_POST['direccion'] ?? ''),
        ]);

        flash_set('success', 'Cliente actualizado.');
        redirect('/clientes');
    }

    public function delete(int $id): void
    {
        Cliente::delete($id);
        flash_set('success', 'Cliente eliminado.');
        redirect('/clientes');
    }
}

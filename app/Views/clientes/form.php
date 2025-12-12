<?php
$isEdit = ($mode ?? 'create') === 'edit';
$action = $isEdit
  ? url('/clientes/' . $cliente['id_cliente'] . '/update')
  : url('/clientes');
?>

<h2 class="text-xl font-bold mb-4"><?= $isEdit ? 'Editar cliente' : 'Crear cliente' ?></h2>

<form method="POST" action="<?= $action ?>" class="bg-white border rounded-lg p-4 space-y-3">
  <div>
    <label class="block text-sm font-medium mb-1">Nombre *</label>
    <input name="nombre" required class="w-full border rounded px-3 py-2"
           value="<?= e($cliente['nombre'] ?? '') ?>">
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
    <div>
      <label class="block text-sm font-medium mb-1">Teléfono</label>
      <input name="telefono" class="w-full border rounded px-3 py-2"
             value="<?= e($cliente['telefono'] ?? '') ?>">
    </div>
    <div>
      <label class="block text-sm font-medium mb-1">Email</label>
      <input name="email" type="email" class="w-full border rounded px-3 py-2"
             value="<?= e($cliente['email'] ?? '') ?>">
    </div>
  </div>

  <div>
    <label class="block text-sm font-medium mb-1">Dirección</label>
    <input name="direccion" class="w-full border rounded px-3 py-2"
           value="<?= e($cliente['direccion'] ?? '') ?>">
  </div>

  <div class="flex gap-2">
    <button class="px-4 py-2 rounded bg-blue-600 text-white">Guardar</button>
    <a href="<?= url('/clientes') ?>" class="px-4 py-2 rounded bg-slate-200">Volver</a>
  </div>
</form>

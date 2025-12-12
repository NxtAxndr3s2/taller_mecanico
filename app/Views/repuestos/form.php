<?php
$isEdit = ($mode ?? 'create') === 'edit';
$action = $isEdit
  ? url('/repuestos/' . $repuesto['id_repuesto'] . '/update')
  : url('/repuestos');
?>

<h2 class="text-xl font-bold mb-4"><?= $isEdit ? 'Editar repuesto' : 'Crear repuesto' ?></h2>

<form method="POST" action="<?= $action ?>" class="bg-white border rounded-lg p-4 space-y-3">
  <div>
    <label class="block text-sm font-medium mb-1">Nombre *</label>
    <input name="nombre" required class="w-full border rounded px-3 py-2"
           value="<?= e($repuesto['nombre'] ?? '') ?>">
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
    <div>
      <label class="block text-sm font-medium mb-1">Stock</label>
      <input name="stock" type="number" min="0" class="w-full border rounded px-3 py-2"
             value="<?= e((string)($repuesto['stock'] ?? 0)) ?>">
    </div>
    <div>
      <label class="block text-sm font-medium mb-1">Precio *</label>
      <input name="precio" type="number" step="0.01" min="0" required
             class="w-full border rounded px-3 py-2"
             value="<?= e((string)($repuesto['precio'] ?? 0)) ?>">
    </div>
  </div>

  <div class="flex gap-2">
    <button class="px-4 py-2 rounded bg-blue-600 text-white">Guardar</button>
    <a href="<?= url('/repuestos') ?>" class="px-4 py-2 rounded bg-slate-200">Volver</a>
  </div>
</form>

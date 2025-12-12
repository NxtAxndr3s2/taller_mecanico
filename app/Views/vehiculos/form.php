<?php
$isEdit = ($mode ?? 'create') === 'edit';
$action = $isEdit
  ? url('/vehiculos/' . $vehiculo['id_vehiculo'] . '/update')
  : url('/vehiculos');
?>

<h2 class="text-xl font-bold mb-4"><?= $isEdit ? 'Editar vehículo' : 'Crear vehículo' ?></h2>

<form method="POST" action="<?= $action ?>" class="bg-white border rounded-lg p-4 space-y-3">
  <div>
    <label class="block text-sm font-medium mb-1">Cliente *</label>
    <select name="id_cliente" required class="w-full border rounded px-3 py-2">
      <option value="">-- Selecciona --</option>
      <?php foreach ($clientes as $c): ?>
        <option value="<?= e((string)$c['id_cliente']) ?>"
          <?= ((string)($vehiculo['id_cliente'] ?? '') === (string)$c['id_cliente']) ? 'selected' : '' ?>>
          <?= e($c['nombre']) ?> (ID <?= e((string)$c['id_cliente']) ?>)
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
    <div>
      <label class="block text-sm font-medium mb-1">Placa *</label>
      <input name="placa" required class="w-full border rounded px-3 py-2"
             value="<?= e($vehiculo['placa'] ?? '') ?>" placeholder="ABC123">
    </div>
    <div>
      <label class="block text-sm font-medium mb-1">Año</label>
      <input name="año" type="number" min="1900" max="2100"
             class="w-full border rounded px-3 py-2"
             value="<?= e((string)($vehiculo['año'] ?? '')) ?>">
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
    <div>
      <label class="block text-sm font-medium mb-1">Marca</label>
      <input name="marca" class="w-full border rounded px-3 py-2"
             value="<?= e((string)($vehiculo['marca'] ?? '')) ?>">
    </div>
    <div>
      <label class="block text-sm font-medium mb-1">Modelo</label>
      <input name="modelo" class="w-full border rounded px-3 py-2"
             value="<?= e((string)($vehiculo['modelo'] ?? '')) ?>">
    </div>
  </div>

  <div class="flex gap-2">
    <button class="px-4 py-2 rounded bg-blue-600 text-white">Guardar</button>
    <a href="<?= url('/vehiculos') ?>" class="px-4 py-2 rounded bg-slate-200">Volver</a>
  </div>
</form>

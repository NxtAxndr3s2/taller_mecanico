<?php
$isEdit = ($mode ?? 'create') === 'edit';
$action = $isEdit
  ? url('/ordenes/' . $orden['id_orden'] . '/update')
  : url('/ordenes');
?>

<h2 class="text-xl font-bold mb-4"><?= $isEdit ? 'Editar orden' : 'Crear orden' ?></h2>

<form method="POST" action="<?= $action ?>" class="bg-white border rounded-lg p-4 space-y-3">

  <div>
    <label class="block text-sm font-medium mb-1">Vehículo *</label>
    <select name="id_vehiculo" required class="w-full border rounded px-3 py-2">
      <option value="">-- Selecciona --</option>
      <?php foreach ($vehiculos as $v): ?>
        <option value="<?= e((string)$v['id_vehiculo']) ?>"
          <?= ((string)($orden['id_vehiculo'] ?? '') === (string)$v['id_vehiculo']) ? 'selected' : '' ?>>
          <?= e($v['cliente_nombre'] . ' - ' . $v['placa']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
    <div>
      <label class="block text-sm font-medium mb-1">Fecha *</label>
      <input type="date" name="fecha" required class="w-full border rounded px-3 py-2"
             value="<?= e($orden['fecha'] ?? date('Y-m-d')) ?>">
    </div>

    <div>
      <label class="block text-sm font-medium mb-1">Estado *</label>
      <select name="estado" class="w-full border rounded px-3 py-2">
        <?php
          $estados = ['abierta'=>'Abierta', 'en_proceso'=>'En proceso', 'cerrada'=>'Cerrada'];
          $current = (string)($orden['estado'] ?? 'abierta');
        ?>
        <?php foreach ($estados as $k => $label): ?>
          <option value="<?= e($k) ?>" <?= $current === $k ? 'selected' : '' ?>>
            <?= e($label) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div>
      <label class="block text-sm font-medium mb-1">Total</label>
      <input type="number" step="0.01" min="0" name="total"
             class="w-full border rounded px-3 py-2"
             value="<?= e((string)($orden['total'] ?? 0)) ?>">
    </div>
  </div>

  <div class="flex gap-2">
    <button class="px-4 py-2 rounded bg-blue-600 text-white">Guardar</button>
    <a href="<?= url('/ordenes') ?>" class="px-4 py-2 rounded bg-slate-200">Volver</a>
  </div>
</form>

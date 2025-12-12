<?php
// Variables disponibles:
// $orden, $detalles, $repuestos
$cerrada = ((string)$orden['estado'] === 'cerrada');
?>

<div class="flex items-center justify-between mb-4">
  <div>
    <h2 class="text-xl font-bold">Orden #<?= e((string)$orden['id_orden']) ?></h2>
    <p class="text-slate-600 text-sm">
      <?= e($orden['cliente_nombre']) ?> • <?= e($orden['vehiculo_placa']) ?>
    </p>
  </div>

  <a href="<?= url('/ordenes') ?>" class="px-4 py-2 rounded bg-slate-200 hover:bg-slate-300">
    Volver
  </a>
</div>

<!-- Resumen -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
  <div class="bg-white border rounded-lg p-4">
    <div class="text-xs text-slate-500">Fecha</div>
    <div class="font-semibold"><?= e($orden['fecha']) ?></div>
  </div>

  <div class="bg-white border rounded-lg p-4">
    <div class="text-xs text-slate-500">Estado</div>
    <?php
      $estado = (string)$orden['estado'];
      $badge = 'bg-slate-200 text-slate-800';
      if ($estado === 'abierta') $badge = 'bg-amber-100 text-amber-800';
      if ($estado === 'en_proceso') $badge = 'bg-blue-100 text-blue-800';
      if ($estado === 'cerrada') $badge = 'bg-green-100 text-green-800';
    ?>
    <span class="inline-block mt-1 px-2 py-1 rounded text-xs font-semibold <?= $badge ?>">
      <?= e($estado) ?>
    </span>

    <!-- Cambiar estado -->
    <form method="POST" action="<?= url('/ordenes/'.$orden['id_orden'].'/estado') ?>" class="mt-2 flex gap-2">
      <select name="estado" class="border rounded px-2 py-1 text-sm">
        <?php foreach (['abierta'=>'Abierta','en_proceso'=>'En proceso','cerrada'=>'Cerrada'] as $k=>$v): ?>
          <option value="<?= $k ?>" <?= $estado === $k ? 'selected' : '' ?>><?= $v ?></option>
        <?php endforeach; ?>
      </select>
      <button class="px-3 py-1 text-sm rounded bg-slate-900 text-white hover:bg-slate-800">
        Cambiar
      </button>
    </form>
  </div>

  <div class="bg-white border rounded-lg p-4">
    <div class="text-xs text-slate-500">Total</div>
    <div class="font-semibold">$<?= e(number_format((float)$orden['total'], 2)) ?></div>
  </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

  <!-- AGREGAR REPUESTO -->
  <div class="bg-white border rounded-lg p-4">
    <h3 class="font-bold mb-3">Agregar repuesto</h3>

    <?php if ($cerrada): ?>
      <div class="mb-3 p-3 rounded bg-red-50 border border-red-200 text-red-700 text-sm">
        Esta orden está cerrada. No se pueden agregar repuestos.
      </div>
    <?php endif; ?>

    <form method="POST"
          action="<?= url('/ordenes/'.$orden['id_orden'].'/repuestos') ?>"
          class="space-y-3 <?= $cerrada ? 'opacity-60 pointer-events-none' : '' ?>">

      <div>
        <label class="block text-sm font-medium mb-1">Repuesto *</label>
<select name="id_repuesto" required class="w-full border rounded px-3 py-2">
  <option value="">-- Selecciona --</option>

  <?php foreach ($repuestos as $r): ?>
    <?php
      $stock = (int)$r['stock'];
      $disabled = $stock <= 0 ? 'disabled' : '';
      $label = $stock <= 0 ? 'SIN STOCK' : "Stock: $stock";
      $style = $stock <= 0 ? 'color:#dc2626; font-weight:bold;' : '';
    ?>
    <option value="<?= e((string)$r['id_repuesto']) ?>"
            <?= $disabled ?>
            style="<?= $style ?>">
      <?= e($r['nombre']) ?> (<?= $label ?>) -
      $<?= e(number_format((float)$r['precio'], 2)) ?>
    </option>
  <?php endforeach; ?>
</select>

<p class="text-xs text-slate-500 mt-1">
  Los repuestos en <span class="text-red-600 font-semibold">rojo</span> no tienen stock y no se pueden seleccionar.
</p>


      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Cantidad *</label>
        <input name="cantidad" type="number" min="1" required
               class="w-full border rounded px-3 py-2" value="1">
      </div>

      <button <?= $cerrada ? 'disabled' : '' ?>
              class="px-4 py-2 rounded text-white
                     <?= $cerrada ? 'bg-slate-400 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700' ?>">
        Agregar
      </button>
    </form>
  </div>

  <!-- REPUESTOS AGREGADOS -->
  <div class="bg-white border rounded-lg p-4">
    <h3 class="font-bold mb-3">Repuestos en la orden</h3>

    <table class="w-full text-sm">
      <thead class="bg-slate-100">
        <tr>
          <th class="p-2 text-left">Repuesto</th>
          <th class="p-2 text-left">Cant.</th>
          <th class="p-2 text-left">P.Unit</th>
          <th class="p-2 text-left">Subtotal</th>
          <th class="p-2 text-left">Acción</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($detalles)): ?>
          <tr><td colspan="5" class="p-2 text-slate-500">Sin repuestos.</td></tr>
        <?php else: ?>
          <?php foreach ($detalles as $d): ?>
            <?php $sub = (int)$d['cantidad'] * (float)$d['precio_unitario']; ?>
            <tr class="border-t">
              <td class="p-2"><?= e($d['repuesto_nombre']) ?></td>
              <td class="p-2"><?= e((string)$d['cantidad']) ?></td>
              <td class="p-2">$<?= e(number_format((float)$d['precio_unitario'],2)) ?></td>
              <td class="p-2">$<?= e(number_format((float)$sub,2)) ?></td>
              <td class="p-2">
                <form method="POST"
                      action="<?= url('/ordenes/'.$orden['id_orden'].'/repuestos/'.$d['id_orden_repuesto'].'/delete') ?>"
                      onsubmit="return confirm('¿Quitar este repuesto?');">
                  <button <?= $cerrada ? 'disabled' : '' ?>
                          class="px-3 py-1 rounded text-white
                                 <?= $cerrada ? 'bg-slate-400 cursor-not-allowed' : 'bg-red-600 hover:bg-red-700' ?>">
                    Quitar
                  </button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<div class="flex items-center justify-between mb-4">
  <h2 class="text-xl font-bold">Órdenes</h2>
  <a href="<?= url('/ordenes/create') ?>" class="px-4 py-2 rounded bg-blue-600 text-white">Nueva</a>
</div>

<div class="bg-white border rounded-lg overflow-hidden">
  <table class="w-full text-sm">
    <thead class="bg-slate-100">
      <tr>
        <th class="text-left p-3">ID</th>
        <th class="text-left p-3">Cliente</th>
        <th class="text-left p-3">Vehículo</th>
        <th class="text-left p-3">Fecha</th>
        <th class="text-left p-3">Estado</th>
        <th class="text-left p-3">Total</th>
        <th class="text-left p-3">Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($ordenes)): ?>
        <tr><td class="p-3 text-slate-500" colspan="7">Sin órdenes todavía.</td></tr>
      <?php else: ?>
        <?php foreach ($ordenes as $o): ?>
          <tr class="border-t">
            <td class="p-3"><?= e((string)$o['id_orden']) ?></td>
            <td class="p-3 font-medium"><?= e($o['cliente_nombre']) ?></td>
            <td class="p-3"><?= e($o['vehiculo_placa']) ?></td>
            <td class="p-3"><?= e($o['fecha']) ?></td>
            <td class="p-3">
              <?php
                $estado = (string)$o['estado'];
                $badge = 'bg-slate-200 text-slate-800';
                if ($estado === 'abierta') $badge = 'bg-amber-100 text-amber-800';
                if ($estado === 'en_proceso') $badge = 'bg-blue-100 text-blue-800';
                if ($estado === 'cerrada') $badge = 'bg-green-100 text-green-800';
              ?>
              <span class="px-2 py-1 rounded text-xs font-semibold <?= $badge ?>">
                <?= e($estado) ?>
              </span>
            </td>
            <td class="p-3">$<?= e(number_format((float)$o['total'], 2)) ?></td>
           <td class="p-3">
  <div class="flex gap-1">
    
    <!-- VER -->
    <a href="<?= url('/ordenes/'.$o['id_orden']) ?>"
       class="px-2 py-1 text-xs rounded bg-indigo-600 text-white hover:bg-indigo-700">
      Ver
    </a>

    <!-- EDITAR -->
    <a href="<?= url('/ordenes/'.$o['id_orden'].'/edit') ?>"
       class="px-2 py-1 text-xs rounded bg-slate-200 hover:bg-slate-300">
      Editar
    </a>

    <!-- ELIMINAR -->
    <form method="POST"
          action="<?= url('/ordenes/'.$o['id_orden'].'/delete') ?>"
          onsubmit="return confirm('¿Eliminar esta orden?');">
      <button class="px-2 py-1 text-xs rounded bg-red-600 text-white hover:bg-red-700">
        Eliminar
      </button>
    </form>

  </div>
</td>

            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<div class="flex items-center justify-between mb-4">
  <h2 class="text-xl font-bold">Vehículos</h2>
  <a href="<?= url('/vehiculos/create') ?>" class="px-4 py-2 rounded bg-blue-600 text-white">Nuevo</a>
</div>

<div class="bg-white border rounded-lg overflow-hidden">
  <table class="w-full text-sm">
    <thead class="bg-slate-100">
      <tr>
        <th class="text-left p-3">ID</th>
        <th class="text-left p-3">Cliente</th>
        <th class="text-left p-3">Placa</th>
        <th class="text-left p-3">Marca</th>
        <th class="text-left p-3">Modelo</th>
        <th class="text-left p-3">Año</th>
        <th class="text-left p-3">Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($vehiculos)): ?>
        <tr><td class="p-3 text-slate-500" colspan="7">Sin vehículos todavía.</td></tr>
      <?php else: ?>
        <?php foreach ($vehiculos as $v): ?>
          <tr class="border-t">
            <td class="p-3"><?= e((string)$v['id_vehiculo']) ?></td>
            <td class="p-3 font-medium"><?= e($v['cliente_nombre']) ?></td>
            <td class="p-3"><?= e($v['placa']) ?></td>
            <td class="p-3"><?= e((string)$v['marca']) ?></td>
            <td class="p-3"><?= e((string)$v['modelo']) ?></td>
            <td class="p-3"><?= e((string)$v['año']) ?></td>
            <td class="p-3">
              <div class="flex gap-2">
                <a class="px-3 py-1 rounded bg-slate-200 hover:bg-slate-300"
                   href="<?= url('/vehiculos/'.$v['id_vehiculo'].'/edit') ?>">Editar</a>

                <form method="POST"
                      action="<?= url('/vehiculos/'.$v['id_vehiculo'].'/delete') ?>"
                      onsubmit="return confirm('¿Eliminar este vehículo?');">
                  <button class="px-3 py-1 rounded bg-red-600 text-white hover:bg-red-700">Eliminar</button>
                </form>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>

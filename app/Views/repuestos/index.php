<div class="flex items-center justify-between mb-4">
  <h2 class="text-xl font-bold">Repuestos</h2>
  <a href="<?= url('/repuestos/create') ?>" class="px-4 py-2 rounded bg-blue-600 text-white">Nuevo</a>
</div>

<div class="bg-white border rounded-lg overflow-hidden">
  <table class="w-full text-sm">
    <thead class="bg-slate-100">
      <tr>
        <th class="text-left p-3">ID</th>
        <th class="text-left p-3">Nombre</th>
        <th class="text-left p-3">Stock</th>
        <th class="text-left p-3">Precio</th>
        <th class="text-left p-3">Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($repuestos)): ?>
        <tr><td class="p-3 text-slate-500" colspan="5">Sin repuestos todavía.</td></tr>
      <?php else: ?>
        <?php foreach ($repuestos as $r): ?>
          <tr class="border-t">
            <td class="p-3"><?= e((string)$r['id_repuesto']) ?></td>
            <td class="p-3 font-medium"><?= e($r['nombre']) ?></td>
            <td class="p-3">
              <?php if ((int)$r['stock'] <= 0): ?>
                <span class="px-2 py-1 rounded text-xs font-semibold bg-red-100 text-red-800">Sin stock</span>
              <?php else: ?>
                <span class="px-2 py-1 rounded text-xs font-semibold bg-green-100 text-green-800"><?= e((string)$r['stock']) ?></span>
              <?php endif; ?>
            </td>
            <td class="p-3">$<?= e(number_format((float)$r['precio'], 2)) ?></td>
            <td class="p-3">
              <div class="flex gap-2">
                <a class="px-3 py-1 rounded bg-slate-200 hover:bg-slate-300"
                   href="<?= url('/repuestos/'.$r['id_repuesto'].'/edit') ?>">Editar</a>

                <form method="POST" action="<?= url('/repuestos/'.$r['id_repuesto'].'/delete') ?>"
                      onsubmit="return confirm('¿Eliminar este repuesto?');">
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

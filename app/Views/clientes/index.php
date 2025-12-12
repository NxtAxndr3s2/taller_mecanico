<div class="flex items-center justify-between mb-4">
  <h2 class="text-xl font-bold">Clientes</h2>
  <a href="<?= url('/clientes/create') ?>" class="px-4 py-2 rounded bg-blue-600 text-white">Nuevo</a>
</div>

<div class="bg-white border rounded-lg overflow-hidden">
  <table class="w-full text-sm">
    <thead class="bg-slate-100">
      <tr>
        <th class="text-left p-3">ID</th>
        <th class="text-left p-3">Nombre</th>
        <th class="text-left p-3">Teléfono</th>
        <th class="text-left p-3">Email</th>
        <th class="text-left p-3">Dirección</th>
        <th class="text-left p-3">Acciones</th>
      </tr>
    </thead>

    <tbody>
      <?php if (empty($clientes)): ?>
        <tr>
          <td class="p-3 text-slate-500" colspan="6">Sin clientes todavía.</td>
        </tr>
      <?php else: ?>
        <?php foreach ($clientes as $c): ?>
          <tr class="border-t">
            <td class="p-3"><?= e((string)$c['id_cliente']) ?></td>
            <td class="p-3 font-medium"><?= e($c['nombre']) ?></td>
            <td class="p-3"><?= e((string)$c['telefono']) ?></td>
            <td class="p-3"><?= e((string)$c['email']) ?></td>
            <td class="p-3"><?= e((string)$c['direccion']) ?></td>

            <!-- ✅ Acciones -->
            <td class="p-3">
              <div class="flex gap-2">
                <a
                  class="px-3 py-1 rounded bg-slate-200 hover:bg-slate-300"
                  href="<?= url('/clientes/'.$c['id_cliente'].'/edit') ?>"
                >
                  Editar
                </a>

                <form
                  method="POST"
                  action="<?= url('/clientes/'.$c['id_cliente'].'/delete') ?>"
                  onsubmit="return confirm('¿Eliminar este cliente?');"
                >
                  <button class="px-3 py-1 rounded bg-red-600 text-white hover:bg-red-700">
                    Eliminar
                  </button>
                </form>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>

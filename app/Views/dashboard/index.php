<div class="bg-white border rounded-lg p-6">
  <h2 class="text-xl font-bold mb-2">Dashboard</h2>

  <?php $u = auth_user(); ?>
  <p class="text-slate-600">
    Hola, <strong><?= e($u['name'] ?? 'Admin') ?></strong> 👋
  </p>

  <p class="mt-4 text-sm text-slate-500">
    Desde aquí puedes acceder a Clientes, Vehículos, Órdenes y Repuestos.
  </p>

  <form method="POST" action="<?= url('/logout') ?>" class="mt-6">
    <button class="px-4 py-2 rounded bg-slate-900 text-white hover:bg-slate-800">
      Cerrar sesión
    </button>
  </form>
</div>

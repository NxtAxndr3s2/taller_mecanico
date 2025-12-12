<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://cdn.tailwindcss.com"></script>
  <title><?= e(config('app.name', 'App')) ?></title>
</head>
<body class="bg-slate-50 text-slate-900">

  <div class="max-w-5xl mx-auto p-6">
    <header class="mb-6 flex items-center justify-between">
      <h1 class="text-2xl font-bold">Taller Mecánico</h1>
      <nav  class="space-x-4">
        <?php if (auth_check()): $u = auth_user(); ?>
  <span class="text-sm text-slate-600">Hola, <strong><?= e($u['name']) ?></strong></span>
<?php endif; ?>

<?php if (auth_check()): ?>
  <a class="text-blue-600 hover:underline" href="<?= url('/dashboard') ?>">Dashboard</a>
<?php endif; ?>


        <a class="text-blue-600 hover:underline" href="<?= url('/repuestos') ?>">Repuestos</a>

       <a class="text-blue-600 hover:underline" href="<?= url('/ordenes') ?>">Órdenes</a>

        <a class="text-blue-600 hover:underline" href="<?= url('/vehiculos') ?>">Vehículos</a>

        <a class="text-blue-600 hover:underline" href="<?= url('/clientes') ?>">Clientes</a>
      </nav>
    </header>

    <?php if ($flash = flash_get()): ?>
      <div class="mb-4 p-3 rounded bg-green-100 border border-green-200">
        <?= e($flash['message']) ?>
      </div>
    <?php endif; ?>

    <main>
      <?php require $viewFile; ?>
    </main>
  </div>

</body>
</html>

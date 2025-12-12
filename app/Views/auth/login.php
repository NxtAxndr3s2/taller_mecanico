<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch">
  <!-- Lado izquierdo: “dashboard/marketing bonito” -->
  <section class="bg-white border rounded-2xl p-8 shadow-sm">
    <h2 class="text-3xl font-extrabold tracking-tight">Panel del Taller</h2>
    <p class="mt-3 text-slate-600">
      Controla clientes, vehículos, órdenes y repuestos con un flujo simple y rápido.
    </p>

    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
      <div class="p-4 rounded-xl bg-slate-50 border">
        <div class="text-sm text-slate-500">Módulos</div>
        <div class="font-semibold">Clientes • Vehículos • Órdenes</div>
      </div>
      <div class="p-4 rounded-xl bg-slate-50 border">
        <div class="text-sm text-slate-500">Repuestos</div>
        <div class="font-semibold">Stock + Asignación por orden</div>
      </div>
    </div>

    <div class="mt-6 p-4 rounded-xl bg-gradient-to-r from-slate-900 to-slate-700 text-white">
      <div class="text-sm opacity-90">Demo</div>
      <div class="text-xl font-bold">admin / admin123</div>
      <div class="text-xs opacity-80 mt-1">Inicia sesión a la derecha</div>
    </div>
  </section>

  <!-- Lado derecho: login -->
  <aside class="bg-white border rounded-2xl p-8 shadow-sm">
    <h3 class="text-xl font-bold">Iniciar sesión</h3>
    <p class="text-sm text-slate-600 mt-1">Accede como administrador.</p>

    <form method="POST" action="<?= url('/login') ?>" class="mt-6 space-y-3">
      <div>
        <label class="block text-sm font-medium mb-1">Usuario</label>
        <input name="username" class="w-full border rounded-lg px-3 py-2" required>
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Contraseña</label>
        <input name="password" type="password" class="w-full border rounded-lg px-3 py-2" required>
      </div>

      <button class="w-full px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
        Entrar
      </button>
    </form>

    <div class="mt-6 text-sm text-slate-600">
      <div class="font-semibold">Usuario demo:</div>
      <div>admin / admin123</div>
    </div>
  </aside>
</div>

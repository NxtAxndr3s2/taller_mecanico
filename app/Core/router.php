<?php
class Router {
    private array $routes = [];

    public function get(string $pattern, array $handler): void {
        $this->add('GET', $pattern, $handler);
    }

    public function post(string $pattern, array $handler): void {
        $this->add('POST', $pattern, $handler);
    }

    private function add(string $method, string $pattern, array $handler): void {
        $this->routes[] = ['method'=>$method, 'pattern'=>$pattern, 'handler'=>$handler];
    }

    public function dispatch(string $method, string $uri): void {
        $path = parse_url($uri, PHP_URL_PATH) ?? '/';

        // soporta: /taller_mecanico/public/index.php/clientes
        $path = str_replace('/taller_mecanico/public', '', $path);
        $path = str_replace('/index.php', '', $path);

        $path = '/' . ltrim($path, '/');
        $path = rtrim($path, '/') ?: '/';

        foreach ($this->routes as $r) {
            if ($r['method'] !== strtoupper($method)) continue;

            $regex = $this->toRegex($r['pattern']);
            if (preg_match($regex, $path, $matches)) {
                array_shift($matches);
                [$class, $action] = $r['handler'];
                call_user_func_array([new $class, $action], $matches);
                return;
            }
        }

        http_response_code(404);
        echo "404 - Ruta no encontrada: " . htmlspecialchars($path);
    }

    private function toRegex(string $pattern): string {
        $pattern = '/' . ltrim($pattern, '/');
        $pattern = rtrim($pattern, '/') ?: '/';
        // {id} -> ([0-9]+)
        $pattern = preg_replace('#\{[a-zA-Z_][a-zA-Z0-9_]*\}#', '([0-9]+)', $pattern);
        return '#^' . $pattern . '$#';
    }
}

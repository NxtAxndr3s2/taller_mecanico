<?php
class Controller {
    protected function view(string $view, array $data = []): void {
        // Variables para la vista
        extract($data, EXTR_SKIP);

        // Ruta de la vista
        $viewFile = __DIR__ . '/../Views/' . $view . '.php';
        if (!file_exists($viewFile)) {
            http_response_code(500);
            echo "Vista no encontrada: " . htmlspecialchars($viewFile);
            return;
        }

        // Cargar layout (layout incluye $viewFile UNA vez)
        require __DIR__ . '/../Views/layout.php';
    }
}

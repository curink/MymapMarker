<?php
namespace App\Core;

class View {
    public static function render(string $view, array $data = []) {
        $path = __DIR__ . '/../../views/' . str_replace('.', '/', $view) . '.php';

        if (!file_exists($path)) {
            http_response_code(500);
            echo "View not found: $view";
            return;
        }

        extract($data);
        require $path;
    }
}
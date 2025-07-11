<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/routes.php';

$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$method = $_SERVER['REQUEST_METHOD'];

// Override method jika ada _method
if ($method === 'POST' && isset($_POST['_method'])) {
    $method = strtoupper($_POST['_method']);
} /*elseif ($method === 'GET' && isset($_GET['_method'])) {
    $method = strtoupper($_GET['_method']);
}*/

// 🔹 Serve static files (assets)
$assetPath = __DIR__ . '/public/' . $uri;
if (file_exists($assetPath) && !is_dir($assetPath)) {
    $ext = pathinfo($assetPath, PATHINFO_EXTENSION);

    $mimeTypes = [
        'css'  => 'text/css',
        'js'   => 'application/javascript',
        'png'  => 'image/png',
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'svg'  => 'image/svg+xml',
        'woff' => 'font/woff',
        'ttf'  => 'font/ttf',
    ];

    $mime = $mimeTypes[$ext] ?? mime_content_type($assetPath);
    header("Content-Type: $mime");
    readfile($assetPath);
    exit;
}

// 🔹 Cek method (GET, POST, dst)
if (isset($routes[$method])) {
    foreach ($routes[$method] as $pattern => $handler) {
        $regex = preg_replace('#\{([\w]+)\}#', '([\w-]+)', $pattern);
        $regex = "#^$regex$#";

        if (preg_match($regex, $uri, $matches)) {
            array_shift($matches);
            [$controllerClass, $action] = $handler;

            if (class_exists($controllerClass) && method_exists($controllerClass, $action)) {
                call_user_func_array([new $controllerClass, $action], $matches);
                return;
            }
        }
    }
}

// 🔹 Tidak cocok
http_response_code(404);
echo "404 - Page not found";
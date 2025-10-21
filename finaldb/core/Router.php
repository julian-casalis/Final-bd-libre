<?php
// 📌 index.php (router principal)

$controller = $_GET['controller'] ?? 'ClienteController';
$action     = $_GET['action'] ?? 'index';

$projectRoot = dirname(__DIR__);
$controllerFile = $projectRoot . "/App/Controllers/{$controller}.php";

try {
    if (!file_exists($controllerFile)) {
        throw new Exception("Archivo de controlador no encontrado: {$controllerFile}");
    }

    require_once $controllerFile;

    if (!class_exists($controller)) {
        throw new Exception("Clase de controlador no encontrada: {$controller}");
    }

    $obj = new $controller();

    if (!method_exists($obj, $action)) {
        throw new Exception("Método no encontrado: {$action} en {$controller}");
    }

    // 🚀 --- NUEVO BLOQUE CLAVE ---
    // Si hay un parámetro "id" en la URL, lo pasa al método
    if (isset($_GET['id'])) {
        $obj->$action($_GET['id']);
    } else {
        $obj->$action();
    }
} catch (Exception $e) {
    echo "<h2 style='color:red;'>🚨 Error MVC:</h2><p>{$e->getMessage()}</p>";
}

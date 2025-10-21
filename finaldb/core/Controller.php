<?php
class Controller
{
    // Carga una vista
    protected function view($view, $data = [])
    {
        extract($data);

        $viewFile = __DIR__ . "/../app/views/{$view}.php";

        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            echo "<p style='color:red;'>❌ Vista no encontrada: {$viewFile}</p>";
        }
    }

    // Carga un modelo
    protected function model($model)
    {
        $modelFile = __DIR__ . "/../app/models/{$model}.php";

        if (file_exists($modelFile)) {
            require_once $modelFile;
            return new $model();
        } else {
            echo "<p style='color:red;'>❌ Modelo no encontrado: {$modelFile}</p>";
        }
    }

    // Redirección
    protected function redirect($url)
    {
        header("Location: $url");
        exit;
    }
}

<?php
ob_start();
session_start();
if (headers_sent()) {
    echo "ERROR CRÍTICO: Se envió contenido al navegador antes de llamar a header().";
    die();
}


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/core/Router.php';
// require_once __DIR__ . '/../app/Router.php';
ob_end_flush();

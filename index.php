<?php
session_start();
require_once 'config/config.php';

// Obtener URI sin parámetros
$request = $_SERVER['REQUEST_URI'];
$uri = parse_url($request, PHP_URL_PATH);

// Quita slash inicial y extensión .php
$page = trim($uri, '/');
$page = preg_replace('/\.php$/', '', $page);

// Páginas públicas (sin login)
$public_pages = ['', 'login', 'logout'];

// Si no tiene sesión y no es pública, redirige
if (!in_array($page, $public_pages) && !isset($_SESSION['user_id'])) {
  header("Location: /login");
  exit;
}

// Enrutamiento
switch ($page) {
  case '':
    header("Location: /login");
    exit;

  case 'login':
    include 'login.php'; // Lógica + HTML embebido si ya uniste todo
    break;

  case 'logout':
    session_destroy();
    header("Location: /login");
    break;

  case 'actualizar':
    include 'actualizar.php';
    break;

  case 'guardar_actualizacion':
    include 'guardar_actualizacion.php';
    break;

  case 'encuesta':
    include 'encuesta.html';
    break;

  case 'procesar_encuesta':
    include 'procesar_encuesta.php';
    break;

  case 'admin':
    if ($_SESSION['role'] === 'admin') {
      include 'admin/panel_admin.html';
    } else {
      echo "Acceso denegado.";
    }
    break;

  default:
    http_response_code(404);
    echo "Página no encontrada.";
    break;
}

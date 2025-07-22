<?php
require 'config/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'egresado') {
  header('Location: login.html');
  exit;
}

$email   = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$celular = trim($_POST['celular']);
$userId  = $_SESSION['user_id'];

if ($email && preg_match('/^9\d{8}$/', $celular)) {
  $upd = $pdo->prepare('UPDATE users SET email = ?, celular = ? WHERE id = ?');
  $upd->execute([$email, $celular, $userId]);
  header('Location: encuesta.html');
  exit;
}

header('Location: actualizar.php');
exit;

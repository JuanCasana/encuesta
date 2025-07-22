<?php
require 'config/config.php';
session_start();

// Verificar sesi¨®n activa
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'egresado') {
  header('Location: login.html');
  exit;
}

$user_id = $_SESSION['user_id'];

// Verificar si ya complet¨® la encuesta
$stmt = $pdo->prepare("SELECT id FROM encuestas WHERE user_id = ?");
$stmt->execute([$user_id]);
if ($stmt->fetch()) {
  // Ya respondi¨®, no volver a guardar
  header('Location: gracias.html');
  exit;
}

// Recolectar todos los datos del formulario
$correo     = $_POST['correo'] ?? null;
$nombre     = $_POST['nombre'] ?? null;
$dni        = $_POST['dni'] ?? null;
$celular    = $_POST['celular'] ?? null;
$direccion  = $_POST['direccion'] ?? null;
$distrito   = $_POST['distrito'] ?? null;
$programa   = $_POST['programa'] ?? null;
$anio       = $_POST['anio_egreso'] ?? null;

$trabaja    = $_POST['trabaja'] ?? null;

$ingreso            = $_POST['ingreso'] ?? null;
$empresa            = $_POST['empresa'] ?? null;
$empresa_contacto   = $_POST['empresa_contacto'] ?? null;
$tipo_empresa       = $_POST['tipo_empresa'] ?? null;
$rubro              = $_POST['rubro'] ?? null;
$area_corresponde   = $_POST['area_corresponde'] ?? null;
$cargo              = $_POST['cargo'] ?? null;
$contrato           = $_POST['contrato'] ?? null;
$tiempo_empresa     = $_POST['tiempo_empresa'] ?? null;
$empleos_tenidos    = $_POST['empleos_tenidos'] ?? null;
$labores_anteriores = $_POST['labores_anteriores'] ?? null;

$tiempo_sin_trabajar  = $_POST['tiempo_sin_trabajar'] ?? null;
$razon_no_trabaja     = $_POST['razon_no_trabaja'] ?? null;
$detalle_no_oferta    = $_POST['detalle_no_oferta'] ?? null;

$formacion_adecuada    = $_POST['formacion_adecuada'] ?? null;
$satisfecho            = $_POST['satisfecho'] ?? null;
$razon_insatisfaccion  = isset($_POST['razon_insatisfaccion']) ? implode(',', $_POST['razon_insatisfaccion']) : null;
$autoeval_trabajo      = $_POST['autoeval_trabajo'] ?? null;
$comparacion_otros     = $_POST['comparacion_otros'] ?? null;
$detalle_no_preparado  = $_POST['detalle_no_preparado'] ?? null;
$porcentaje_aplicado   = $_POST['porcentaje_aplicado'] ?? null;
$faltante              = isset($_POST['faltante']) ? implode(',', $_POST['faltante']) : null;

// Guardar en la base de datos
$stmt = $pdo->prepare("INSERT INTO encuestas (
  user_id, correo, nombre, dni, celular, direccion, distrito, programa, anio_egreso,
  trabaja, ingreso, empresa, empresa_contacto, tipo_empresa, rubro, area_corresponde,
  cargo, contrato, tiempo_empresa, empleos_tenidos, labores_anteriores,
  tiempo_sin_trabajar, razon_no_trabaja, detalle_no_oferta,
  formacion_adecuada, satisfecho, razon_insatisfaccion, autoeval_trabajo,
  comparacion_otros, detalle_no_preparado, porcentaje_aplicado, faltante
) VALUES (
  ?, ?, ?, ?, ?, ?, ?, ?, ?,
  ?, ?, ?, ?, ?, ?, ?,
  ?, ?, ?, ?, ?,
  ?, ?, ?,
  ?, ?, ?, ?,
  ?, ?, ?, ?
)");

$stmt->execute([
  $user_id, $correo, $nombre, $dni, $celular, $direccion, $distrito, $programa, $anio,
  $trabaja, $ingreso, $empresa, $empresa_contacto, $tipo_empresa, $rubro, $area_corresponde,
  $cargo, $contrato, $tiempo_empresa, $empleos_tenidos, $labores_anteriores,
  $tiempo_sin_trabajar, $razon_no_trabaja, $detalle_no_oferta,
  $formacion_adecuada, $satisfecho, $razon_insatisfaccion, $autoeval_trabajo,
  $comparacion_otros, $detalle_no_preparado, $porcentaje_aplicado, $faltante
]);

// Redirigir a p¨¢gina de gracias
header('Location: gracias.html');
exit;


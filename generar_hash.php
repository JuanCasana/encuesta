<?php
$password = 'Prueba123!';
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Hash generado para '$password':<br><code>$hash</code>";
?>

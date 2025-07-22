<?php
session_start();
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Cerrando sesión...</title>
  <script>
    // Limpiar localStorage del navegador
    localStorage.clear();

    // Eliminar historial anterior (bloquear botón atrás)
    history.pushState(null, '', location.href);
    window.addEventListener('popstate', () => {
      history.pushState(null, '', location.href);
    });

    // Redirigir después de una pausa breve para asegurar limpieza
    setTimeout(() => {
      window.location.href = "login.html";
    }, 200);
  </script>
</head>
<body>
  Cerrando sesión...
</body>
</html>

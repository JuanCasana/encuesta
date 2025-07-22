<?php
require 'config/config.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'egresado') {
  header('Location: login.php');
  exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT dni, email, celular, CONCAT(nombres, ' ', apellidos) AS nombre_completo FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
  echo "Usuario no encontrado.";
  exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Actualiza tus datos</title>
  <link rel="stylesheet" href="estilo-formularios.css" />
  <style>
    body {
      background-color: #f4f7f9;
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 40px 0;
    }

    .container {
      max-width: 600px;
      background-color: #ffffff;
      margin: auto;
      padding: 30px 40px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    h2 {
      text-align: center;
      color: #043b71;
      margin-bottom: 10px;
    }

    #saludo {
      text-align: center;
      font-size: 1.1rem;
      font-weight: bold;
      color: #333;
      margin-bottom: 20px;
    }

    label {
      display: block;
      margin-top: 10px;
      font-weight: bold;
      color: #333;
    }

    input {
      width: 100%;
      padding: 8px;
      font-size: 1rem;
      border-radius: 4px;
      border: 1px solid #ccc;
      margin-top: 5px;
    }

    .invalid-message {
      color: red;
      font-size: 0.9em;
      margin-top: 8px;
    }

    button {
      margin-top: 30px;
      width: 100%;
      padding: 12px;
      font-size: 1rem;
      background-color: #043b71;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-weight: bold;
    }

    button:hover {
      background-color: #012c55;
    }

    #password-strength {
      height: 8px;
      background: #ddd;
      border-radius: 5px;
      overflow: hidden;
      margin-top: 5px;
    }

    #password-strength .bar {
      height: 100%;
      transition: width 0.3s ease-in-out;
    }

    #password-message {
      font-size: 0.85rem;
      color: red;
      margin-top: 5px;
      display: none;
    }
  </style>
</head>
<body>
    <script>
    // Redirige si ya actualizó
    if (localStorage.getItem("actualizacion_completa") === "true") {
      window.location.href = "encuesta.html";
    }

    // Bloquear botón "atrás"
    history.replaceState(null, '', location.href);
    window.addEventListener('popstate', () => {
      location.href = 'encuesta.html';
    });
  </script>
  
  <div class="container">
    <h2>Actualiza tus datos</h2>
    <p id="saludo">Hola, <?php echo htmlspecialchars($usuario['nombre_completo']); ?> 👋</p>

    <form method="POST" action="guardar_actualizacion.php" id="actualizarForm">
      

      <label for="email">Correo electrónico:</label>
      <input type="email" name="email" id="email" value="<?= htmlspecialchars($usuario['email']) ?>" required />

      <label for="celular">Celular (9 dígitos):</label>
      <input type="text" name="celular" id="celular" pattern="^9\d{8}$" value="<?= htmlspecialchars($usuario['celular']) ?>" required />

      <label for="password">Nueva contraseña (opcional):</label>
      <input type="password" name="password" id="password" />

      <div id="password-strength"><div class="bar" style="width: 0%; background-color: transparent;"></div></div>
      <div id="password-message"></div>

      <button type="submit">Guardar y continuar</button>
    </form>
  </div>

  <!-- Guardar datos en localStorage para usar en encuesta.html -->
  <script>
    localStorage.setItem('userData', JSON.stringify({
      nombre_completo: <?= json_encode($usuario['nombre_completo']) ?>,
      dni: <?= json_encode($usuario['dni']) ?>,
      email: <?= json_encode($usuario['email']) ?>,
      celular: <?= json_encode($usuario['celular']) ?>
    }));
  </script>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const pwdInput = document.getElementById('password');
      const pwdBar = document.querySelector('#password-strength .bar');
      const pwdMsg = document.getElementById('password-message');

      pwdInput.addEventListener('input', () => {
        const val = pwdInput.value;
        const checks = [
          /[A-Z]/.test(val),
          /[a-z]/.test(val),
          /\d/.test(val),
          /[\W_]/.test(val),
          val.length >= 8
        ];

        const passed = checks.filter(Boolean).length;
        const colors = ['#d33', '#f90', '#ffcc00', '#0c0', '#0a0'];
        pwdBar.style.width = (passed / 5) * 100 + '%';
        pwdBar.style.backgroundColor = colors[Math.max(passed - 1, 0)];

        if (val.length > 0) {
          pwdMsg.style.display = 'block';
          pwdMsg.innerHTML = `
            ${checks[4] ? '✅' : '❌'} Al menos 8 caracteres<br>
            ${checks[0] ? '✅' : '❌'} Una mayúscula<br>
            ${checks[1] ? '✅' : '❌'} Una minúscula<br>
            ${checks[2] ? '✅' : '❌'} Un número<br>
            ${checks[3] ? '✅' : '❌'} Un carácter especial
          `;
        } else {
          pwdMsg.style.display = 'none';
        }
      });
    });
  </script>
</body>
</html>

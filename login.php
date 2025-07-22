<?php
require 'config/config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $dni = $_POST['dni'] ?? '';
  $password = $_POST['password'] ?? '';

  $stmt = $pdo->prepare('SELECT * FROM users WHERE dni = ? LIMIT 1');
  $stmt->execute([$dni]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['role'] = $user['role'];

    if ($user['role'] === 'egresado') {
      $check = $pdo->prepare("SELECT id FROM encuestas WHERE user_id = ?");
      $check->execute([$user['id']]);

      header('Location: actualizar.php');
      exit;
    } else {
      header('Location: admin/panel_admin.html');
      exit;
    }
  } else {
    header('Location: login.php?error=1');
    exit;
  }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Inicio de Sesión – IESTP “Cesar Augusto Guardía Mayorga”</title>
  <link rel="stylesheet" href="estilo-formularios.css" />
  <style>
    #global-error {
      display: none;
      color: red;
      text-align: center;
      margin-bottom: 1em;
      font-size: 0.95rem;
    }

    .invalid-message {
      color: red;
      font-size: 0.85em;
      margin-top: 5px;
      display: none;
    }

    input.invalid + .invalid-message {
      display: block;
    }

    .password-input-wrapper {
      position: relative;
    }

    .toggle-password {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      font-size: 18px;
    }
  </style>
</head>
<body>
  <div class="login-wrapper">
    <div class="login-form">
      <h2>Inicio de Sesión</h2>

      <!-- Mensaje de error global -->
      <div id="global-error"></div>

      <form method="POST" action="login.php" novalidate>
        <label for="dni">DNI (8 dígitos):</label>
        <input
          type="text"
          name="dni"
          id="dni"
          required
          pattern="^\d{8}$"
          title="Ingrese 8 dígitos numéricos"
        />
        <div class="invalid-message">Ingrese su DNI (8 dígitos).</div>

        <label for="password">Contraseña:</label>
        <div class="password-input-wrapper">
          <input type="password" name="password" id="password" required />
          <span class="toggle-password" id="togglePassword" aria-label="Mostrar contraseña">👁️</span>
        </div>
        <div class="invalid-message">Ingrese su contraseña.</div>

        <button type="submit">Ingresar</button>
      </form>
    </div>

    <div class="logo-side">
      <img src="logo-cesar.png" alt="Logo IESTP Cesar Augusto Guardía Mayorga" />
      <h3 style="line-height: 1.4;">
        Instituto de Educación Superior<br />
        Tecnológico Público<br />
        “Cesar Augusto Guardía Mayorga”
      </h3>
    </div>
  </div>

  <script>
    const form = document.querySelector('form');
    const globalErr = document.getElementById('global-error');
    const pwdInput = document.getElementById('password');
    const toggleBtn = document.getElementById('togglePassword');

    // Mostrar error según parámetro GET
    const params = new URLSearchParams(window.location.search);
    if (params.has('error')) {
      let msg = '';
      switch(params.get('error')) {
        case 'dni':
          msg = 'DNI inválido. Debe tener 8 dígitos.';
          break;
        case 'credentials':
          msg = 'DNI o contraseña incorrectos.';
          break;
      }
      if (msg) {
        globalErr.textContent = msg;
        globalErr.style.display = 'block';
      }
    }

    // Validación personalizada del formulario
    form.addEventListener('submit', function(e) {
      let valid = true;
      globalErr.style.display = 'none';

      this.querySelectorAll('input').forEach(el => {
        el.classList.remove('invalid');
        if (!el.checkValidity()) {
          el.classList.add('invalid');
          valid = false;
        }
      });

      if (!valid) {
        e.preventDefault();
        globalErr.textContent = 'Por favor complete todos los datos correctamente.';
        globalErr.style.display = 'block';
      }
    });

    // Mostrar/Ocultar contraseña
    toggleBtn.addEventListener('click', () => {
      const isPwd = pwdInput.type === 'password';
      pwdInput.type = isPwd ? 'text' : 'password';
      toggleBtn.textContent = isPwd ? '🙈' : '👁️';
    });
  </script>
</body>
</html>
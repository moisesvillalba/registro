<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Iniciar sesión con configuraciones seguras
session_start([
    'cookie_httponly' => true,     
    'cookie_secure' => false,       // Change this to false for local development
    'cookie_samesite' => 'Lax'     
]);

// Incluir el sistema de mensajes flash
require_once 'includes/flash.php';

// Limpiar mensajes de error previos
$error_login = "";

// Procesar el formulario de inicio de sesión
if (isset($_POST['ingresar'])) {
    // Protección contra ataques de fuerza bruta
    // Retrasar la respuesta ligeramente (0.2 segundos)
    usleep(200000);
    
    // Obtener y limpiar datos de entrada
    $usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    // Credenciales de demostración (en producción esto vendría de la base de datos)
    if ($usuario === 'admin' && $password === 'Admin123') {
        // Crear una sesión segura con token aleatorio
        $_SESSION['usuario_id'] = 1;
        $_SESSION['usuario_nombre'] = 'Administrador';
        $_SESSION['usuario_rol'] = 'admin';
        $_SESSION['token'] = bin2hex(random_bytes(32)); // Token único por sesión
        $_SESSION['ultimo_acceso'] = time(); // Para control de inactividad
        
        // Registrar inicio de sesión exitoso (en producción)
        // logAccesoExitoso($usuario, $_SERVER['REMOTE_ADDR']);
        
        // Mensaje de bienvenida
        setFlashMessage('success', '¡Bienvenido al sistema, Administrador!');
        
        // Redirigir a la lista administrativa
        header("Location: list.php");
        exit();
    } else {
        // Mensaje de error genérico (no específico para no dar pistas a atacantes)
        $error_login = "Usuario o contraseña incorrectos";
        
        // Registrar intento fallido (en producción)
        // logIntentoFallido($usuario, $_SERVER['REMOTE_ADDR']);
    }
}

// Verificar si hay un mensaje flash para mostrar (por ejemplo, de cierre de sesión)
$flashMessage = isset($_SESSION['flash_message']) ? getFlashMessage() : null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Acceso administrativo al Sistema de Registro">
    <meta name="theme-color" content="#1e3a8a">
    <title>Iniciar Sesión | Sistema de Registro</title>
    
    <!-- Precargar recursos críticos -->
    <link rel="preload" href="assets/css/styles.css" as="style">
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/webfonts/fa-solid-900.woff2" as="font" type="font/woff2" crossorigin>
    
    <!-- Cargar CSS -->
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/validation.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <div class="login-logo">
            <i class="fas fa-users fa-3x" style="color: var(--primary-color); margin-bottom: 15px;"></i>
            <h1>Sistema de Registro</h1>
            <p>Acceso administrativo</p>
        </div>
        
        <?php if ($flashMessage): ?>
        <div class="mensaje-container <?php echo $flashMessage['tipo']; ?>" role="alert">
            <?php echo $flashMessage['mensaje']; ?>
        </div>
        <?php endif; ?>
        
        <form method="POST" action="" id="login-form" autocomplete="off">
            <!-- Campo oculto para protección CSRF (en producción usar un token real) -->
            <input type="hidden" name="csrf_token" value="<?php echo isset($_SESSION['token']) ? $_SESSION['token'] : bin2hex(random_bytes(32)); ?>">
            
            <?php if ($error_login): ?>
                <div class="mensaje-container error" role="alert" aria-live="assertive">
                    <div class="mensaje-icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="mensaje-content">
                        <?php echo $error_login; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <div class="form-group">
                <label for="usuario">
                    <i class="fas fa-user"></i> Usuario:
                </label>
                <input type="text" id="usuario" name="usuario" required 
                       autocomplete="username" 
                       placeholder="Ingrese su nombre de usuario"
                       value="<?php echo isset($usuario) ? htmlspecialchars($usuario) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="password">
                    <i class="fas fa-lock"></i> Contraseña:
                </label>
                <div class="password-container">
                    <input type="password" id="password" name="password" required 
                           autocomplete="current-password" 
                           placeholder="Ingrese su contraseña">
                    <button type="button" class="toggle-password" aria-label="Mostrar contraseña">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
            
            <div class="btn-container">
                <button type="submit" name="ingresar" class="btn btn-submit">
                    <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                </button>
            </div>
        </form>
        
        <p class="login-footer">
            <a href="index.php">
                <i class="fas fa-home"></i> Volver al Formulario Principal
            </a>
        </p>
        
        <div class="login-help">
            <p>Acceso de demostración:</p>
            <p>Usuario: <strong>admin</strong> | Contraseña: <strong>Admin123</strong></p>
        </div>
    </div>
    
    <!-- Scripts -->
    <script>
    // Función para mostrar/ocultar contraseña
    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.querySelector('.toggle-password');
        const passwordInput = document.getElementById('password');
        
        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function() {
                // Cambiar tipo de input
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                // Cambiar icono
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });
        }
    });
    
    // Registrar Service Worker para funcionalidad offline
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', () => {
        navigator.serviceWorker.register('/service-worker.js')
          .then(registration => {
            console.log('Service Worker registrado');
          })
          .catch(error => {
            console.log('Error al registrar Service Worker:', error);
          });
      });
    }
    </script>
    
    <style>
    /* Estilos específicos de la página de login */
    .login-container {
        max-width: 450px;
        padding: 2rem;
    }
    
    .login-logo {
        margin-bottom: 2rem;
    }
    
    .login-logo h1 {
        font-size: 1.8rem;
        margin-bottom: 0.5rem;
    }
    
    .login-logo p {
        color: var(--text-muted);
    }
    
    .mensaje-container {
        display: flex;
        padding: 15px;
        border-radius: var(--border-radius-sm);
        margin-bottom: 20px;
        align-items: center;
    }
    
    .mensaje-container.error {
        background-color: #fee2e2;
        border-left: 4px solid #ef4444;
        color: #b91c1c;
    }
    
    .mensaje-container.success {
        background-color: #dcfce7;
        border-left: 4px solid #10b981;
        color: #047857;
    }
    
    .mensaje-icon {
        font-size: 20px;
        margin-right: 15px;
    }
    
    .password-container {
        position: relative;
    }
    
    .toggle-password {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        color: var(--text-muted);
    }
    
    .toggle-password:hover {
        color: var(--primary-color);
    }
    
    .login-footer {
        text-align: center;
        margin-top: 20px;
        font-size: 14px;
    }
    
    .login-help {
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid var(--border-color);
        text-align: center;
        font-size: 13px;
        color: var(--text-muted);
    }
    
    .btn-submit {
        width: 100%;
        padding: 12px;
    }
    </style>
</body>
</html>
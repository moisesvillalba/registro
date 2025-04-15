<?php
// Iniciar sesión
session_start();

// Para demostración, se usa un acceso rápido con token
if (isset($_POST['ingresar'])) {
    // En producción, deberías validar contra la base de datos
    $usuario = $_POST['usuario'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Credenciales de demostración
    if ($usuario === 'admin' && $password === 'Admin123') {
        // Redirigir a la lista con token de acceso
        header("Location: list.php?token=demo123");
        exit();
    } else {
        $error_login = "Usuario o contraseña incorrectos";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="login-container">
        <div class="login-logo">
            <h2>Sistema de Registro</h2>
        </div>
        
        <form method="POST" action="">
            <?php if (isset($error_login)): ?>
                <div class="error-message" style="text-align: center; margin-bottom: 15px;">
                    <?php echo $error_login; ?>
                </div>
            <?php endif; ?>
            
            <div class="form-group">
                <label for="usuario">Usuario:</label>
                <input type="text" id="usuario" name="usuario" required>
            </div>
            
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="btn-container">
                <button type="submit" name="ingresar" class="btn btn-submit">Iniciar Sesión</button>
            </div>
        </form>
        
        <p style="text-align: center; margin-top: 20px; font-size: 14px;">
            <a href="index.php">Volver al Formulario</a>
        </p>
    </div>
</body>
</html>
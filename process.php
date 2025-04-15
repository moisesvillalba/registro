<?php
// Iniciar sesión
session_start();

// Incluir archivo de configuración de la base de datos
require_once 'config/database.php';

// Incluir el sistema de mensajes flash
require_once 'includes/flash.php';

// Función para limpiar datos de entrada
function limpiarDato($dato) {
    $dato = trim($dato);
    $dato = stripslashes($dato);
    $dato = htmlspecialchars($dato);
    return $dato;
}

// Procesar los datos del formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Crear instancia de la base de datos y obtener conexión
    $database = new Database();
    $db = $database->getConnection();
    
    // DATOS PERSONALES
    $ci = limpiarDato($_POST["ci"]);
    $nombre = limpiarDato($_POST["nombre"]);
    $apellido = limpiarDato($_POST["apellido"]);
    $fecha_nacimiento = limpiarDato($_POST["fecha_nacimiento"]);
    $lugar = limpiarDato($_POST["lugar"]);
    $profesion = limpiarDato($_POST["profesion"]);
    $direccion = limpiarDato($_POST["direccion"]);
    $telefono = limpiarDato($_POST["telefono"]);
    $ciudad = limpiarDato($_POST["ciudad"]);
    $barrio = limpiarDato($_POST["barrio"]);
    $esposa = isset($_POST["esposa"]) ? limpiarDato($_POST["esposa"]) : "";
    $hijos = isset($_POST["hijos"]) ? limpiarDato($_POST["hijos"]) : "";
    $madre = isset($_POST["madre"]) ? limpiarDato($_POST["madre"]) : "";
    $padre = isset($_POST["padre"]) ? limpiarDato($_POST["padre"]) : "";
    
    // DATOS LABORALES
    $direccion_laboral = limpiarDato($_POST["direccion_laboral"]);
    $empresa = isset($_POST["empresa"]) ? limpiarDato($_POST["empresa"]) : "";
    
    // DATOS LOGIALES - Ajustados para coincidir con los campos del formulario
    $institucion_actual = limpiarDato($_POST["logia_actual"]);
    $nivel_actual = limpiarDato($_POST["grado_masonico"]);
    $nivel_superior = isset($_POST["grado_capitular"]) ? limpiarDato($_POST["grado_capitular"]) : "";
    $fecha_ingreso = limpiarDato($_POST["fecha_iniciacion"]);
    $institucion_ingreso = limpiarDato($_POST["logia_iniciacion"]);
    
    // DATOS MÉDICOS
    $grupo_sanguineo = limpiarDato($_POST["grupo_sanguineo"]);
    $enfermedades_base = isset($_POST["enfermedades_base"]) ? limpiarDato($_POST["enfermedades_base"]) : "";
    $seguro_privado = isset($_POST["seguro_privado"]) ? limpiarDato($_POST["seguro_privado"]) : "No";
    $ips = isset($_POST["ips"]) ? limpiarDato($_POST["ips"]) : "No";
    $alergias = isset($_POST["alergias"]) ? limpiarDato($_POST["alergias"]) : "";
    $numero_emergencia = limpiarDato($_POST["numero_emergencia"]);
    $contacto_emergencia = limpiarDato($_POST["contacto_emergencia"]);
    
    // Manejo de la subida de documentos (mejorado con validación de tipos y tamaños)
    $ruta_documentos = "";
    
    if (isset($_FILES["certificados"]) && is_array($_FILES["certificados"]["name"])) {
        $directorio_destino = "uploads/documentos/";
        
        // Crear el directorio si no existe
        if (!file_exists($directorio_destino)) {
            mkdir($directorio_destino, 0777, true);
        }
        
        $rutas_archivos = [];
        
        // Procesar cada archivo
        for ($i = 0; $i < count($_FILES["certificados"]["name"]); $i++) {
            if ($_FILES["certificados"]["error"][$i] == 0) {
                // Obtener extensión del archivo
                $extension = strtolower(pathinfo($_FILES["certificados"]["name"][$i], PATHINFO_EXTENSION));
                // Lista de extensiones permitidas
                $extensiones_permitidas = ['jpg', 'jpeg', 'png', 'pdf'];
                
                // Verificar extensión
                if (in_array($extension, $extensiones_permitidas)) {
                    // Verificar tamaño (máximo 5MB)
                    if ($_FILES["certificados"]["size"][$i] <= 5 * 1024 * 1024) {
                        // Generar nombre único con timestamp para evitar colisiones
                        $nombre_archivo = uniqid('doc_') . '_' . time() . '.' . $extension;
                        $ruta_archivo = $directorio_destino . $nombre_archivo;
                        
                        // Mover el archivo
                        if (move_uploaded_file($_FILES["certificados"]["tmp_name"][$i], $ruta_archivo)) {
                            $rutas_archivos[] = $ruta_archivo;
                        }
                    } else {
                        $mensaje_error = "El archivo " . $_FILES["certificados"]["name"][$i] . " excede el tamaño máximo permitido (5MB).";
                        throw new Exception($mensaje_error);
                    }
                } else {
                    $mensaje_error = "El tipo de archivo " . $_FILES["certificados"]["name"][$i] . " no está permitido. Use JPG, PNG o PDF.";
                    throw new Exception($mensaje_error);
                }
            }
        }
        
        // Convertir el array de rutas a string (separado por comas)
        if (!empty($rutas_archivos)) {
            $ruta_documentos = implode(",", $rutas_archivos);
        }
    }
    
    try {
        // Verificar si el CI ya existe para evitar duplicados
        $check_sql = "SELECT id FROM miembros WHERE ci = :ci";
        $check_stmt = $db->prepare($check_sql);
        $check_stmt->bindParam(':ci', $ci);
        $check_stmt->execute();
        
        if ($check_stmt->rowCount() > 0) {
            $mensaje_error = "Ya existe un registro con este número de CI. Por favor, verifique los datos.";
            throw new Exception($mensaje_error);
        }
        
        // Comenzar transacción para asegurar integridad de datos
        $db->beginTransaction();
        
        // Preparar la consulta SQL para insertar datos
        $sql = "INSERT INTO miembros (
                    ci, nombre, apellido, fecha_nacimiento, lugar, profesion, direccion, 
                    telefono, ciudad, barrio, esposa, hijos, madre, padre,
                    direccion_laboral, empresa,
                    institucion_actual, nivel_actual, nivel_superior, fecha_ingreso, institucion_ingreso, documentos,
                    grupo_sanguineo, enfermedades_base, seguro_privado, ips, alergias, numero_emergencia, contacto_emergencia
                ) VALUES (
                    :ci, :nombre, :apellido, :fecha_nacimiento, :lugar, :profesion, :direccion, 
                    :telefono, :ciudad, :barrio, :esposa, :hijos, :madre, :padre,
                    :direccion_laboral, :empresa,
                    :institucion_actual, :nivel_actual, :nivel_superior, :fecha_ingreso, :institucion_ingreso, :documentos,
                    :grupo_sanguineo, :enfermedades_base, :seguro_privado, :ips, :alergias, :numero_emergencia, :contacto_emergencia
                )";
                
        $stmt = $db->prepare($sql);
        
        // Vincular parámetros
        $stmt->bindParam(':ci', $ci);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':fecha_nacimiento', $fecha_nacimiento);
        $stmt->bindParam(':lugar', $lugar);
        $stmt->bindParam(':profesion', $profesion);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':ciudad', $ciudad);
        $stmt->bindParam(':barrio', $barrio);
        $stmt->bindParam(':esposa', $esposa);
        $stmt->bindParam(':hijos', $hijos);
        $stmt->bindParam(':madre', $madre);
        $stmt->bindParam(':padre', $padre);
        $stmt->bindParam(':direccion_laboral', $direccion_laboral);
        $stmt->bindParam(':empresa', $empresa);
        $stmt->bindParam(':institucion_actual', $institucion_actual);
        $stmt->bindParam(':nivel_actual', $nivel_actual);
        $stmt->bindParam(':nivel_superior', $nivel_superior);
        $stmt->bindParam(':fecha_ingreso', $fecha_ingreso);
        $stmt->bindParam(':institucion_ingreso', $institucion_ingreso);
        $stmt->bindParam(':documentos', $ruta_documentos);
        $stmt->bindParam(':grupo_sanguineo', $grupo_sanguineo);
        $stmt->bindParam(':enfermedades_base', $enfermedades_base);
        $stmt->bindParam(':seguro_privado', $seguro_privado);
        $stmt->bindParam(':ips', $ips);
        $stmt->bindParam(':alergias', $alergias);
        $stmt->bindParam(':numero_emergencia', $numero_emergencia);
        $stmt->bindParam(':contacto_emergencia', $contacto_emergencia);
        
        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Confirmar transacción
            $db->commit();
            
            // Guardar mensaje de éxito en la sesión
            setFlashMessage('success', 'Registro creado exitosamente');
            
            // Registro exitoso, redirigir a la página de éxito
            header("Location: success.php");
            exit();
        } else {
            // Revertir transacción en caso de error
            $db->rollBack();
            
            $mensaje_error = "Error al registrar los datos.";
            throw new Exception($mensaje_error);
        }
    } catch (Exception $e) {
        // Si ocurrió un error durante la transacción, revertir cambios
        if ($db->inTransaction()) {
            $db->rollBack();
        }
        
        // Eliminar archivos subidos en caso de error en la base de datos
        if (!empty($rutas_archivos)) {
            foreach ($rutas_archivos as $ruta) {
                if (file_exists($ruta)) {
                    unlink($ruta);
                }
            }
        }
        
        $mensaje_error = $e->getMessage();
        
        // Guardar mensaje de error en la sesión
        setFlashMessage('error', $mensaje_error);
    }
}

// Si llegamos aquí, es porque hubo un error o se accedió directamente a esta página
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Error en el registro de miembro">
    <title>Error en el Registro</title>
    
    <!-- Precargar recursos críticos -->
    <link rel="preload" href="assets/css/styles.css" as="style">
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/webfonts/fa-solid-900.woff2" as="font" type="font/woff2" crossorigin>
    
    <!-- Cargar CSS -->
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/validation.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Error en el Registro</h1>
        </div>
        
        <div class="form-container">
            <?php 
            // Mostrar mensaje de error específico si existe
            if (isset($mensaje_error)): 
            ?>
                <div class="mensaje-container error" role="alert" aria-live="assertive">
                    <div class="mensaje-icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="mensaje-content">
                        <h2>Ha ocurrido un error</h2>
                        <p><?php echo $mensaje_error; ?></p>
                    </div>
                </div>
            <?php 
            // Si no hay mensaje específico, mostrar mensaje de error genérico
            else: 
            ?>
                <div class="mensaje-container error" role="alert">
                    <div class="mensaje-icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="mensaje-content">
                        <h2>Acceso incorrecto</h2>
                        <p>Esta página solo debe ser accedida al enviar el formulario de registro.</p>
                    </div>
                </div>
            <?php endif; ?>
            
            <div class="btn-container">
                <a href="index.php" class="btn">
                    <i class="fas fa-arrow-left"></i> Volver al Formulario
                </a>
            </div>
        </div>
    </div>
    
    <!-- Script para funcionalidad offline -->
    <script>
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
</body>
</html>
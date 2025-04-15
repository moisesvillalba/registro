<?php
// Iniciar sesión
session_start();

// Incluir archivo de configuración de la base de datos
require_once 'config/database.php';

// Verificación de token
if (!isset($_GET['token']) || $_GET['token'] !== 'demo123') {
    header("Location: login.php");
    exit();
}

// Verificar si se proporcionó un ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: list.php?token=demo123");
    exit();
}

$id = $_GET['id'];

// Crear instancia de la base de datos y obtener conexión
$database = new Database();
$db = $database->getConnection();

// Preparar consulta para obtener los datos del miembro
$sql = "SELECT * FROM miembros WHERE id = :id";
$stmt = $db->prepare($sql);
$stmt->bindParam(':id', $id);
$stmt->execute();

// Verificar si existe el registro
if ($stmt->rowCount() == 0) {
    header("Location: list.php?token=demo123");
    exit();
}

// Obtener los datos del miembro
$miembro = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Registro</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        .detail-section {
            margin-bottom: 30px;
        }
        
        .detail-title {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 8px;
            padding-bottom: 8px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .detail-row {
            margin-bottom: 15px;
            display: flex;
            flex-wrap: wrap;
        }
        
        .detail-item {
            flex: 1 0 300px;
            margin-right: 20px;
            margin-bottom: 10px;
        }
        
        .detail-label {
            font-weight: 600;
            color: var(--secondary-color);
            display: block;
            margin-bottom: 3px;
        }
        
        .detail-value {
            color: var(--text-color);
        }
        
        .document-preview {
            margin-top: 10px;
        }
        
        .document-preview img {
            max-width: 100%;
            max-height: 300px;
            border: 1px solid var(--border-color);
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Detalles del Registro</h1>
        </div>
        
        <div class="form-container">
            <div class="section">
                <h2 class="section-title">DATOS PERSONALES</h2>
                
                <div class="detail-row">
                    <div class="detail-item">
                        <span class="detail-label">CI:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($miembro['ci']); ?></span>
                    </div>
                    
                    <div class="detail-item">
                        <span class="detail-label">Nombre:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($miembro['nombre'] . ' ' . $miembro['apellido']); ?></span>
                    </div>
                    
                    <div class="detail-item">
                        <span class="detail-label">Fecha de Nacimiento:</span>
                        <span class="detail-value"><?php echo date('d/m/Y', strtotime($miembro['fecha_nacimiento'])); ?></span>
                    </div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-item">
                        <span class="detail-label">Lugar:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($miembro['lugar']); ?></span>
                    </div>
                    
                    <div class="detail-item">
                        <span class="detail-label">Profesión:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($miembro['profesion']); ?></span>
                    </div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-item">
                        <span class="detail-label">Dirección:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($miembro['direccion']); ?></span>
                    </div>
                    
                    <div class="detail-item">
                        <span class="detail-label">Teléfono:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($miembro['telefono']); ?></span>
                    </div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-item">
                        <span class="detail-label">Ciudad:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($miembro['ciudad']); ?></span>
                    </div>
                    
                    <div class="detail-item">
                        <span class="detail-label">Barrio:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($miembro['barrio']); ?></span>
                    </div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-item">
                        <span class="detail-label">Esposa:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($miembro['esposa'] ?: 'No especificado'); ?></span>
                    </div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-item">
                        <span class="detail-label">Hijos:</span>
                        <span class="detail-value"><?php echo nl2br(htmlspecialchars($miembro['hijos'] ?: 'No especificado')); ?></span>
                    </div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-item">
                        <span class="detail-label">Madre:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($miembro['madre'] ?: 'No especificado'); ?></span>
                    </div>
                    
                    <div class="detail-item">
                        <span class="detail-label">Padre:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($miembro['padre'] ?: 'No especificado'); ?></span>
                    </div>
                </div>
            </div>
            
            <div class="section">
                <h2 class="section-title">DATOS LABORALES</h2>
                
                <div class="detail-row">
                    <div class="detail-item">
                        <span class="detail-label">Dirección Laboral:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($miembro['direccion_laboral']); ?></span>
                    </div>
                    
                    <div class="detail-item">
                        <span class="detail-label">Empresa:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($miembro['empresa'] ?: 'No especificado'); ?></span>
                    </div>
                </div>
            </div>
            
            <div class="section">
                <h2 class="section-title">DATOS INSTITUCIONALES</h2>
                
                <div class="detail-row">
                    <div class="detail-item">
                        <span class="detail-label">Institución Actual:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($miembro['institucion_actual']); ?></span>
                    </div>
                    
                    <div class="detail-item">
                        <span class="detail-label">Nivel Actual:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($miembro['nivel_actual']); ?></span>
                    </div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-item">
                        <span class="detail-label">Nivel Superior:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($miembro['nivel_superior'] ?: 'No especificado'); ?></span>
                    </div>
                    
                    <div class="detail-item">
                        <span class="detail-label">Fecha de Ingreso:</span>
                        <span class="detail-value"><?php echo date('d/m/Y', strtotime($miembro['fecha_ingreso'])); ?></span>
                    </div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-item">
                        <span class="detail-label">Institución de Ingreso:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($miembro['institucion_ingreso']); ?></span>
                    </div>
                </div>
                
                <?php if (!empty($miembro['documentos'])): ?>
                <div class="detail-row">
                    <div class="detail-item">
                        <span class="detail-label">Documentos:</span>
                        <div class="detail-value">
                            <a href="<?php echo htmlspecialchars($miembro['documentos']); ?>" target="_blank">Ver documento</a>
                            <?php
                            $ext = pathinfo($miembro['documentos'], PATHINFO_EXTENSION);
                            if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif'])):
                            ?>
                            <div class="document-preview">
                                <img src="<?php echo htmlspecialchars($miembro['documentos']); ?>" alt="Documento">
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="section">
                <h2 class="section-title">DATOS MÉDICOS</h2>
                
                <div class="detail-row">
                    <div class="detail-item">
                        <span class="detail-label">Grupo Sanguíneo:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($miembro['grupo_sanguineo']); ?></span>
                    </div>
                    
                    <div class="detail-item">
                        <span class="detail-label">Enfermedades Base:</span>
                        <span class="detail-value"><?php echo nl2br(htmlspecialchars($miembro['enfermedades_base'] ?: 'Ninguna')); ?></span>
                    </div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-item">
                        <span class="detail-label">¿Cuenta con Seguro Privado?</span>
                        <span class="detail-value"><?php echo htmlspecialchars($miembro['seguro_privado']); ?></span>
                    </div>
                    
                    <div class="detail-item">
                        <span class="detail-label">¿IPS?</span>
                        <span class="detail-value"><?php echo htmlspecialchars($miembro['ips']); ?></span>
                    </div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-item">
                        <span class="detail-label">Alergias:</span>
                        <span class="detail-value"><?php echo nl2br(htmlspecialchars($miembro['alergias'] ?: 'Ninguna')); ?></span>
                    </div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-item">
                        <span class="detail-label">Número en caso de Emergencias:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($miembro['numero_emergencia']); ?></span>
                    </div>
                    
                    <div class="detail-item">
                        <span class="detail-label">Contacto en Caso de Emergencias:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($miembro['contacto_emergencia']); ?></span>
                    </div>
                </div>
            </div>
            
            <div class="btn-container">
                <a href="list.php?token=<?php echo htmlspecialchars($_GET['token']); ?>" class="btn">Volver a la lista</a>
                <a href="edit.php?id=<?php echo $id; ?>&token=<?php echo htmlspecialchars($_GET['token']); ?>" class="btn btn-edit">Editar</a>
            </div>
        </div>
    </div>
</body>
</html>
<?php
// Iniciar sesión
session_start();

// Incluir archivo de configuración de la base de datos
require_once 'config/database.php';

// Verificación básica (deberías implementar un sistema de autenticación adecuado)
$autenticado = false;

// Para fines de demostración, permitir acceso con un token específico
// NO usar esto en producción, es solo para demostración
if (isset($_GET['token']) && $_GET['token'] === 'demo123') {
    $autenticado = true;
}

if (!$autenticado) {
    // Redirigir a la página de inicio de sesión
    header("Location: login.php");
    exit();
}

// Crear instancia de la base de datos y obtener conexión
$database = new Database();
$db = $database->getConnection();

// Configuración de paginación
$registros_por_pagina = 10;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina_actual - 1) * $registros_por_pagina;

// Consulta para obtener el total de registros
$sql_total = "SELECT COUNT(*) as total FROM miembros";
$stmt_total = $db->prepare($sql_total);
$stmt_total->execute();
$fila_total = $stmt_total->fetch(PDO::FETCH_ASSOC);
$total_registros = $fila_total['total'];
$total_paginas = ceil($total_registros / $registros_por_pagina);

// Consulta por defecto para obtener registros
$sql = "SELECT id, ci, CONCAT(nombre, ' ', apellido) as nombre_completo, 
               fecha_nacimiento, telefono, institucion_actual, nivel_actual, 
               fecha_registro 
        FROM miembros 
        ORDER BY fecha_registro DESC 
        LIMIT :inicio, :registros_por_pagina";

$stmt = $db->prepare($sql);
$stmt->bindParam(':inicio', $inicio, PDO::PARAM_INT);
$stmt->bindParam(':registros_por_pagina', $registros_por_pagina, PDO::PARAM_INT);
$stmt->execute();

// Procesar búsqueda si se envió
$busqueda = '';
if (isset($_GET['buscar']) && !empty($_GET['buscar'])) {
    $busqueda = $_GET['buscar'];
    
    $sql_busqueda = "SELECT id, ci, CONCAT(nombre, ' ', apellido) as nombre_completo, 
                           fecha_nacimiento, telefono, institucion_actual, nivel_actual, 
                           fecha_registro 
                    FROM miembros 
                    WHERE ci LIKE :busqueda OR nombre LIKE :busqueda OR 
                          apellido LIKE :busqueda OR institucion_actual LIKE :busqueda 
                    ORDER BY fecha_registro DESC";
    
    $stmt = $db->prepare($sql_busqueda);
    $param_busqueda = "%" . $busqueda . "%";
    $stmt->bindParam(':busqueda', $param_busqueda);
    $stmt->execute();
    
    // Actualizar total de registros para paginación con búsqueda
    $total_registros = $stmt->rowCount();
    $total_paginas = ceil($total_registros / $registros_por_pagina);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Registros</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Lista de Registros</h1>
            <a href="index.php" class="btn">Nuevo Registro</a>
        </div>
        
        <form class="search-form" method="GET" action="">
            <input type="text" name="buscar" placeholder="Buscar por CI, nombre o institución..." value="<?php echo htmlspecialchars($busqueda); ?>">
            <input type="hidden" name="token" value="<?php echo isset($_GET['token']) ? htmlspecialchars($_GET['token']) : ''; ?>">
            <button type="submit">Buscar</button>
        </form>
        
        <div class="table-container">
            <?php if ($stmt->rowCount() > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>CI</th>
                            <th>Nombre</th>
                            <th>Fecha Nacimiento</th>
                            <th>Teléfono</th>
                            <th>Institución</th>
                            <th>Nivel</th>
                            <th>Fecha Registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($fila['ci']); ?></td>
                                <td><?php echo htmlspecialchars($fila['nombre_completo']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($fila['fecha_nacimiento'])); ?></td>
                                <td><?php echo htmlspecialchars($fila['telefono']); ?></td>
                                <td><?php echo htmlspecialchars($fila['institucion_actual']); ?></td>
                                <td><?php echo htmlspecialchars($fila['nivel_actual']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($fila['fecha_registro'])); ?></td>
                                <td>
                                    <div class="actions">
                                        <a href="view.php?id=<?php echo $fila['id']; ?>&token=<?php echo htmlspecialchars($_GET['token']); ?>" class="btn btn-view">Ver</a>
                                        <a href="edit.php?id=<?php echo $fila['id']; ?>&token=<?php echo htmlspecialchars($_GET['token']); ?>" class="btn btn-edit">Editar</a>
                                        <a href="delete.php?id=<?php echo $fila['id']; ?>&token=<?php echo htmlspecialchars($_GET['token']); ?>" class="btn btn-delete" onclick="return confirm('¿Está seguro de que desea eliminar este registro?');">Eliminar</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                
                <?php if (!isset($_GET['buscar']) || empty($_GET['buscar'])): ?>
                    <div class="pagination">
                        <?php if ($pagina_actual > 1): ?>
                            <a href="?pagina=1&token=<?php echo htmlspecialchars($_GET['token']); ?>">&laquo; Primera</a>
                            <a href="?pagina=<?php echo $pagina_actual - 1; ?>&token=<?php echo htmlspecialchars($_GET['token']); ?>">&lsaquo; Anterior</a>
                        <?php endif; ?>
                        
                        <?php
                        $inicio_paginacion = max(1, $pagina_actual - 2);
                        $fin_paginacion = min($inicio_paginacion + 4, $total_paginas);
                        
                        for ($i = $inicio_paginacion; $i <= $fin_paginacion; $i++):
                        ?>
                            <?php if ($i == $pagina_actual): ?>
                                <span class="active"><?php echo $i; ?></span>
                            <?php else: ?>
                                <a href="?pagina=<?php echo $i; ?>&token=<?php echo htmlspecialchars($_GET['token']); ?>"><?php echo $i; ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>
                        
                        <?php if ($pagina_actual < $total_paginas): ?>
                            <a href="?pagina=<?php echo $pagina_actual + 1; ?>&token=<?php echo htmlspecialchars($_GET['token']); ?>">Siguiente &rsaquo;</a>
                            <a href="?pagina=<?php echo $total_paginas; ?>&token=<?php echo htmlspecialchars($_GET['token']); ?>">Última &raquo;</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div style="text-align: center; padding: 30px;">
                    <p>No se encontraron registros.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
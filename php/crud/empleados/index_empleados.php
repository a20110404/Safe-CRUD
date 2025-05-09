<?php
include("../conexion.php");
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login/index.php");
    exit();
}

// Obtener los permisos del usuario
$user_id = $_SESSION['user_id'];
$query = mysqli_query($con, "
    SELECT r.permiso_agregar, r.permiso_eliminar, r.permiso_escritura, r.permiso_lectura 
    FROM empleados_roles er
    INNER JOIN roles r ON er.id_rol = r.id
    WHERE er.id_empleado = '$user_id'
");
$permisos = mysqli_fetch_assoc($query);

// Si no tiene permiso de lectura, redirigir
if (!$permisos['permiso_lectura']) {
    echo '<div class="alert alert-danger">No tienes permiso para ver esta página.</div>';
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lista de Productos</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .content {
            margin-top: 80px;
        }
    </style>
</head>
<body>
    <?php include("nav_empleados.php"); ?>
    <div class="container">
        <div class="content">
            <h2>Lista de Productos</h2>
            <hr />

            <?php
            // Eliminar un producto (si tiene permiso)
            if (isset($_GET['delete']) && $permisos['permiso_eliminar']) {
                $id = mysqli_real_escape_string($con, (strip_tags($_GET["delete"], ENT_QUOTES)));
                $delete = mysqli_query($con, "DELETE FROM productos WHERE id='$id'") or die(mysqli_error($con));
                if ($delete) {
                    echo '<div class="alert alert-success alert-dismissable d-flex align-items-center"><button type="button" class="btn btn-outline-success me-2" data-dismiss="alert" aria-hidden="true">&times;</button>Producto eliminado correctamente.</div>';
                } else {
                    echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="btn btn-outline-danger me-2" data-dismiss="alert" aria-hidden="true">&times;</button>Error al eliminar el producto.</div>';
                }
            }
            ?>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Fecha de Creación</th>
                            <th>Fecha de Actualización</th>
                            <?php if ($permisos['permiso_escritura'] || $permisos['permiso_eliminar']): ?>
                                <th>Acciones</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = mysqli_query($con, "SELECT * FROM productos ORDER BY id ASC");
                        if (mysqli_num_rows($sql) == 0) {
                            echo '<tr><td colspan="8">No hay productos registrados.</td></tr>';
                        } else {
                            while ($row = mysqli_fetch_assoc($sql)) {
                                echo '
                                <tr>
                                    <td>' . $row['id'] . '</td>
                                    <td>' . $row['nombre_producto'] . '</td>
                                    <td>' . $row['descripcion'] . '</td>
                                    <td>$' . number_format($row['precio'], 2) . '</td>
                                    <td>' . $row['cantidad'] . '</td>
                                    <td>' . $row['fecha_creacion'] . '</td>
                                    <td>' . $row['fecha_actualizacion'] . '</td>';
                                if ($permisos['permiso_escritura'] || $permisos['permiso_eliminar']) {
                                    echo '<td>';
                                    if ($permisos['permiso_escritura']) {
                                        echo '<a href="modificar_productos.php?id=' . $row['id'] . '" class="btn btn-primary btn-sm">Modificar</a> ';
                                    }
                                    if ($permisos['permiso_eliminar']) {
                                        echo '<a href="index_empleados.php?delete=' . $row['id'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'¿Estás seguro de eliminar este producto?\')">Eliminar</a>';
                                    }
                                    echo '</td>';
                                }
                                echo '</tr>';
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <?php if ($permisos['permiso_agregar']): ?>
                <a href="agregar_productos.php" class="btn btn-success">Agregar Producto</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
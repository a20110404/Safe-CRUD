<?php
include("conexion.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestión de Roles</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .content {
            margin-top: 80px;
        }
    </style>
</head>
<body>
    <?php include("nav.php"); ?>
    <div class="container">
        <div class="content">
            <h2>Gestión de Roles</h2>
            <hr />

            <?php
            // Verificar si se está editando un rol
            if (isset($_GET['id'])) {
                $id = mysqli_real_escape_string($con, (strip_tags($_GET["id"], ENT_QUOTES)));
                $query = mysqli_query($con, "SELECT * FROM roles WHERE id='$id'");
                if (mysqli_num_rows($query) == 0) {
                    echo '<div class="alert alert-warning">No se encontró el rol.</div>';
                } else {
                    $row = mysqli_fetch_assoc($query);
                }
            }

            // Actualizar un rol existente
            if (isset($_POST['update_role'])) {
                $id = mysqli_real_escape_string($con, (strip_tags($_POST["id"], ENT_QUOTES)));
                $nombre_rol = mysqli_real_escape_string($con, (strip_tags($_POST["nombre_rol"], ENT_QUOTES)));
                $descripcion = mysqli_real_escape_string($con, (strip_tags($_POST["descripcion"], ENT_QUOTES)));
                $permiso_lectura = isset($_POST["permiso_lectura"]) ? 1 : 0;
                $permiso_escritura = isset($_POST["permiso_escritura"]) ? 1 : 0;
                $permiso_agregar = isset($_POST["permiso_agregar"]) ? 1 : 0;
                $permiso_eliminar = isset($_POST["permiso_eliminar"]) ? 1 : 0;

                $update = mysqli_query($con, "UPDATE roles SET 
                    nombre_rol='$nombre_rol', 
                    descripcion='$descripcion', 
                    permiso_lectura='$permiso_lectura', 
                    permiso_escritura='$permiso_escritura', 
                    permiso_agregar='$permiso_agregar', 
                    permiso_eliminar='$permiso_eliminar' 
                    WHERE id='$id'") or die(mysqli_error($con));

                if ($update) {
                    echo '<div class="alert alert-success">Rol actualizado correctamente.</div>';
                } else {
                    echo '<div class="alert alert-danger">Error al actualizar el rol.</div>';
                }
            }

            // Agregar un nuevo rol
            if (isset($_POST['add_role'])) {
                $nombre_rol = mysqli_real_escape_string($con, (strip_tags($_POST["nombre_rol"], ENT_QUOTES)));
                $descripcion = mysqli_real_escape_string($con, (strip_tags($_POST["descripcion"], ENT_QUOTES)));
                $permiso_lectura = isset($_POST["permiso_lectura"]) ? 1 : 0;
                $permiso_escritura = isset($_POST["permiso_escritura"]) ? 1 : 0;
                $permiso_agregar = isset($_POST["permiso_agregar"]) ? 1 : 0;
                $permiso_eliminar = isset($_POST["permiso_eliminar"]) ? 1 : 0;

                $insert = mysqli_query($con, "INSERT INTO roles(nombre_rol, descripcion, permiso_lectura, permiso_escritura, permiso_agregar, permiso_eliminar) 
                                              VALUES('$nombre_rol', '$descripcion', '$permiso_lectura', '$permiso_escritura', '$permiso_agregar', '$permiso_eliminar')") or die(mysqli_error($con));
                if ($insert) {
                    echo '<div class="alert alert-success">Rol agregado correctamente.</div>';
                } else {
                    echo '<div class="alert alert-danger">Error al agregar el rol.</div>';
                }
            }

            // Eliminar un rol
            if (isset($_GET['delete'])) {
                $id = mysqli_real_escape_string($con, (strip_tags($_GET["delete"], ENT_QUOTES)));
                $delete = mysqli_query($con, "DELETE FROM roles WHERE id='$id'") or die(mysqli_error($con));
                if ($delete) {
                    echo '<div class="alert alert-success">Rol eliminado correctamente.</div>';
                } else {
                    echo '<div class="alert alert-danger">Error al eliminar el rol.</div>';
                }
            }
            ?>

            <!-- Formulario para agregar o editar un rol -->
            <form method="post" class="mb-4">
                <?php if (isset($_GET['id'])): ?>
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                <?php endif; ?>
                <div class="mb-3">
                    <label for="nombre_rol" class="form-label">Nombre del Rol</label>
                    <input type="text" class="form-control" id="nombre_rol" name="nombre_rol" value="<?php echo isset($row['nombre_rol']) ? $row['nombre_rol'] : ''; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required><?php echo isset($row['descripcion']) ? $row['descripcion'] : ''; ?></textarea>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="permiso_lectura" name="permiso_lectura" <?php echo (isset($row['permiso_lectura']) && $row['permiso_lectura'] == 1) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="permiso_lectura">Permisos de Lectura</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="permiso_escritura" name="permiso_escritura" <?php echo (isset($row['permiso_escritura']) && $row['permiso_escritura'] == 1) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="permiso_escritura">Permisos de Escritura</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="permiso_agregar" name="permiso_agregar" <?php echo (isset($row['permiso_agregar']) && $row['permiso_agregar'] == 1) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="permiso_agregar">Permisos para Agregar</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="permiso_eliminar" name="permiso_eliminar" <?php echo (isset($row['permiso_eliminar']) && $row['permiso_eliminar'] == 1) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="permiso_eliminar">Permisos para Eliminar</label>
                </div>
                <button type="submit" name="<?php echo isset($_GET['id']) ? 'update_role' : 'add_role'; ?>" class="btn btn-primary mt-3">
                    <?php echo isset($_GET['id']) ? 'Actualizar Rol' : 'Agregar Rol'; ?>
                </button>
            </form>

            <!-- Tabla para listar roles -->
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre del Rol</th>
                            <th>Descripción</th>
                            <th>Lectura</th>
                            <th>Escritura</th>
                            <th>Agregar</th>
                            <th>Eliminar</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = mysqli_query($con, "SELECT * FROM roles ORDER BY id ASC");
                        if (mysqli_num_rows($sql) == 0) {
                            echo '<tr><td colspan="8">No hay roles registrados.</td></tr>';
                        } else {
                            while ($row = mysqli_fetch_assoc($sql)) {
                                echo '
                                <tr>
                                    <td>' . $row['id'] . '</td>
                                    <td>' . $row['nombre_rol'] . '</td>
                                    <td>' . $row['descripcion'] . '</td>
                                    <td>' . ($row['permiso_lectura'] ? 'Activado' : 'Desactivado') . '</td>
                                    <td>' . ($row['permiso_escritura'] ? 'Activado' : 'Desactivado') . '</td>
                                    <td>' . ($row['permiso_agregar'] ? 'Activado' : 'Desactivado') . '</td>
                                    <td>' . ($row['permiso_eliminar'] ? 'Activado' : 'Desactivado') . '</td>
                                    <td>
                                        <a href="roles.php?id=' . $row['id'] . '" class="btn btn-primary btn-sm">Editar</a>
                                        <a href="roles.php?delete=' . $row['id'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'¿Estás seguro de eliminar este rol?\')">Eliminar</a>
                                    </td>
                                </tr>
                                ';
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div>
        <footer class="text-center mt-4">
            <p>&copy; 2025 CETI. Todos los derechos reservados. - RBAC - Desarrollo de software seguro</p>
        </footer>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
include("conexion.php");

if (isset($_GET['nik'])) {
    $nik = mysqli_real_escape_string($con, (strip_tags($_GET["nik"], ENT_QUOTES)));

    // Obtener datos del empleado
    $sql = mysqli_query($con, "SELECT * FROM empleados WHERE codigo='$nik'");
    if (mysqli_num_rows($sql) == 0) {
        header("Location: index.php");
    } else {
        $row = mysqli_fetch_assoc($sql);
    }

    if (isset($_POST['save'])) {
        $rol = mysqli_real_escape_string($con, (strip_tags($_POST["rol"], ENT_QUOTES)));
        $permisos = $_POST['permisos'] ?? [];

        // Actualizar rol
        $updateRol = mysqli_query($con, "UPDATE empleados SET rol_id='$rol' WHERE codigo='$nik'") or die(mysqli_error($con));

        // Actualizar permisos
        $deletePermisos = mysqli_query($con, "DELETE FROM rol_permiso WHERE rol_id='$rol'") or die(mysqli_error($con));
        foreach ($permisos as $permiso) {
            $insertPermiso = mysqli_query($con, "INSERT INTO rol_permiso (rol_id, permiso_id) VALUES ('$rol', '$permiso')") or die(mysqli_error($con));
        }

        if ($updateRol && $deletePermisos && $insertPermiso) {
            echo '<div class="alert alert-success">Rol y permisos actualizados correctamente.</div>';
        } else {
            echo '<div class="alert alert-danger">Error al actualizar el rol y los permisos.</div>';
        }
    }
} else {
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modificar Permisos</title>
    <link href="../../css/bootstrap.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Modificar Rol y Permisos</h2>
        <form method="post">
            <div class="form-group">
                <label for="rol">Rol:</label>
                <select name="rol" class="form-control" required>
                    <?php
                    // Obtener roles desde la base de datos
                    $roles = mysqli_query($con, "SELECT * FROM roles");
                    while ($role = mysqli_fetch_assoc($roles)) {
                        $selected = ($row['rol_id'] ?? '') == $role['id'] ? 'selected' : '';
                        echo "<option value='{$role['id']}' $selected>{$role['nombre_rol']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="permisos">Permisos:</label>
                <?php
                // Obtener permisos desde la base de datos
                $permisos = mysqli_query($con, "SELECT * FROM permisos");
                $assignedPermisos = []; // Permisos asignados al rol actual
                if (isset($row['rol_id'])) {
                    $result = mysqli_query($con, "SELECT permiso_id FROM rol_permiso WHERE rol_id = {$row['rol_id']}");
                    while ($permiso = mysqli_fetch_assoc($result)) {
                        $assignedPermisos[] = $permiso['permiso_id'];
                    }
                }
                while ($permiso = mysqli_fetch_assoc($permisos)) {
                    $checked = in_array($permiso['id'], $assignedPermisos) ? 'checked' : '';
                    echo "<div class='form-check'>
                            <input class='form-check-input' type='checkbox' name='permisos[]' value='{$permiso['id']}' $checked>
                            <label class='form-check-label'>{$permiso['nombre_permiso']}</label>
                          </div>";
                }
                ?>
            </div>
            <button type="submit" name="save" class="btn btn-primary">Guardar</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>
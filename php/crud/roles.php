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
        $permisos = mysqli_real_escape_string($con, (strip_tags($_POST["permisos"], ENT_QUOTES)));

        // Actualizar permisos
        $update = mysqli_query($con, "UPDATE empleados SET permisos='$permisos' WHERE codigo='$nik'") or die(mysqli_error($con));
        if ($update) {
            echo '<div class="alert alert-success">Permisos actualizados correctamente.</div>';
        } else {
            echo '<div class="alert alert-danger">Error al actualizar los permisos.</div>';
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
        <h2>Modificar Permisos del Rol</h2>
        <form method="post">
            <div class="form-group">
                <label for="permisos">Permisos:</label>
                <textarea name="permisos" class="form-control" rows="5" required><?php echo $row['permisos'] ?? ''; ?></textarea>
            </div>
            <button type="submit" name="save" class="btn btn-primary">Guardar</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>
<?php
include("conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos enviados desde el formulario
    $id_empleado = mysqli_real_escape_string($con, $_POST['codigo_empleado']);
    $id_rol = mysqli_real_escape_string($con, $_POST['role']);

    // Verificar si ya existe una relación entre el empleado y algún rol
    $check_query = "SELECT * FROM empleados_roles WHERE id_empleado='$id_empleado'";
    $check_result = mysqli_query($con, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // Si ya existe una relación, actualizamos el rol
        $update_query = "UPDATE empleados_roles SET id_rol='$id_rol' WHERE id_empleado='$id_empleado'";
        if (mysqli_query($con, $update_query)) {
            header("Location: index.php?mensaje=rol_actualizado");
        } else {
            echo "Error al actualizar el rol: " . mysqli_error($con);
        }
    } else {
        // Si no existe una relación, insertamos una nueva
        $insert_query = "INSERT INTO empleados_roles (id_empleado, id_rol) VALUES ('$id_empleado', '$id_rol')";
        if (mysqli_query($con, $insert_query)) {
            header("Location: index.php?mensaje=rol_asignado");
        } else {
            echo "Error al asignar el rol: " . mysqli_error($con);
        }
    }
}
?>
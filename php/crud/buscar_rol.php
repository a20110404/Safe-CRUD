<?php
include("conexion.php");

if (isset($_GET['codigo_empleado'])) {
    $codigo_empleado = mysqli_real_escape_string($con, $_GET['codigo_empleado']);

    // Buscar el rol asignado al empleado
    $query = "SELECT id_rol FROM empleados_roles WHERE id_empleado = '$codigo_empleado' LIMIT 1";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode(['id_rol' => $row['id_rol']]);
    } else {
        echo json_encode(['id_rol' => null]); // No tiene rol asignado
    }
}
?>
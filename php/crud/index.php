<?php
include("conexion.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <title>Datos de empleados</title>
 
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .content {
            margin-top: 80px;
        }
    </style>
</head>
<body>
    <?php include('nav.php'); ?>
    <div class="container">
        <div class="content">
            <h2>Lista de empleados</h2>
            <hr />

            <?php
            if (isset($_GET['aksi']) == 'delete') {
                $nik = mysqli_real_escape_string($con, (strip_tags($_GET["nik"], ENT_QUOTES)));
                $cek = mysqli_query($con, "SELECT * FROM empleados WHERE codigo='$nik'");
                if (mysqli_num_rows($cek) == 0) {
                    echo '<div class="alert alert-info alert-dismissable d-flex align-items-center"><button type="button" class="btn btn-outline-info me-2" data-dismiss="alert" aria-hidden="true">&times;</button> No se encontraron datos.</div>';
                } else {
                    $delete = mysqli_query($con, "DELETE FROM empleados WHERE codigo='$nik'");
                    if ($delete) {
                        echo '<div class="alert alert-success alert-dismissable d-flex align-items-center"><button type="button" class="btn btn-outline-success me-2" data-dismiss="alert" aria-hidden="true">&times;</button> Datos eliminados correctamente.</div>';
                    } else {
                        echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Error, no se pudo eliminar los datos.</div>';
                    }
                }
            }

            if (isset($_GET['mensaje'])) {
                if ($_GET['mensaje'] == 'relacion_existente') {
                    echo '<div class="alert alert-warning alert-dismissable d-flex align-items-center"><button type="button" class="btn btn-outline-warning me-2" data-dismiss="alert" aria-hidden="true">&times;</button>El empleado ya tiene asignado este rol.</div>';
                } elseif ($_GET['mensaje'] == 'rol_asignado') {
                    echo '<div class="alert alert-success alert-dismissable d-flex align-items-center"><button type="button" class="btn btn-outline-success me-2" data-dismiss="alert" aria-hidden="true">&times;</button>Rol asignado correctamente.</div>';
                } elseif ($_GET['mensaje'] == 'rol_actualizado') {
                    echo '<div class="alert alert-info alert-dismissable d-flex align-items-center"><button type="button" class="btn btn-outline-info me-2" data-dismiss="alert" aria-hidden="true">&times;</button>Rol actualizado con éxito.</div>';
                }
            }
            ?>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <tr>
                        <th>No</th>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Cargo</th>
                        <th>Estado</th>
                        <th>Rol Asignado</th>
                        <th>Acciones</th>
                    </tr>
                    <?php
                    $sql = mysqli_query($con, "
                        SELECT e.*, r.nombre_rol 
                        FROM empleados e
                        LEFT JOIN empleados_roles er ON e.codigo = er.id_empleado
                        LEFT JOIN roles r ON er.id_rol = r.id
                        ORDER BY e.codigo ASC
                    ");
                    if (mysqli_num_rows($sql) == 0) {
                        echo '<tr><td colspan="9">No hay datos.</td></tr>';
                    } else {
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($sql)) {
                            echo '
                            <tr>
                                <td>' . $no . '</td>
                                <td>' . $row['codigo'] . '</td>
                                <td>' . $row['nombres'] . '</td>
                                <td>' . $row['email'] . '</td>
                                <td>' . $row['telefono'] . '</td>
                                <td>' . $row['puesto'] . '</td>
                                <td>';
                            if ($row['estado'] == '1') {
                                echo '<span class="label label-success">Fijo</span>';
                            } else if ($row['estado'] == '2') {
                                echo '<span class="label label-info">Contratado</span>';
                            } else if ($row['estado'] == '3') {
                                echo '<span class="label label-warning">Outsourcing</span>';
                            }
                            echo '
                                </td>
                                <td>' . (!empty($row['nombre_rol']) ? $row['nombre_rol'] : 'No asignado') . '</td>
                                <td>
                                    <a href="edit.php?nik=' . $row['codigo'] . '" title="Editar datos" class="btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i></a>
                                    <a href="index.php?aksi=delete&nik=' . $row['codigo'] . '" title="Eliminar" onclick="return confirm(\'¿Está seguro de borrar los datos de ' . $row['nombres'] . '?\')" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></a>
                                    <button class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#assignRoleModal" data-id="' . $row['codigo'] . '" data-name="' . $row['nombres'] . '"><i class="bi bi-key-fill"></i></button>
                                </td>
                            </tr>
                            ';
                            $no++;
                        }
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal para asignar roles -->
    <div class="modal fade" id="assignRoleModal" tabindex="-1" aria-labelledby="assignRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="assignRoleModalLabel">Asignar Rol</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="assignRoleForm" method="post" action="asignar_rol.php">
                        <!-- Campo oculto para el ID del empleado -->
                        <input type="hidden" name="codigo_empleado" id="codigoEmpleado">
                        
                        <!-- Mostrar el nombre del empleado -->
                        <p id="employeeName"></p>
                        
                        <!-- Selección de roles -->
                        <div class="mb-3">
                            <label for="role" class="form-label">Seleccionar Rol</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="">No asignado</option>
                                <?php
                                // Obtener los roles disponibles desde la base de datos
                                $roles = mysqli_query($con, "SELECT * FROM roles");
                                while ($role = mysqli_fetch_assoc($roles)) {
                                    echo '<option value="' . $role['id'] . '">' . $role['nombre_rol'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        
                        <!-- Botón para asignar el rol -->
                        <button type="submit" class="btn btn-primary">Asignar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Pasar datos al modal
        const assignRoleModal = document.getElementById('assignRoleModal');
        assignRoleModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const codigo = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');

            const codigoEmpleadoInput = assignRoleModal.querySelector('#codigoEmpleado');
            const employeeNameParagraph = assignRoleModal.querySelector('#employeeName');
            const roleSelect = assignRoleModal.querySelector('#role');

            // Asignar los valores al formulario dentro del modal
            codigoEmpleadoInput.value = codigo;
            employeeNameParagraph.textContent = `Asignar rol a: ${name}`;

            // Limpiar selección previa
            roleSelect.value = "";

            // Buscar si el empleado ya tiene un rol asignado
            fetch(`buscar_rol.php?codigo_empleado=${codigo}`)
                .then(response => response.json())
                .then(data => {
                    if (data.id_rol) {
                        roleSelect.value = data.id_rol; // Seleccionar el rol asignado
                    }
                })
                .catch(error => console.error('Error al buscar el rol:', error));
        });
    </script>
</body>
</html>
<?php
include("conexion.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Datos de empleados</title>
 
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <style>
        .content {
            margin-top: 80px;
        }
    </style>
</head>
<body>
    <?php include("nav.php");?>
    <div class="container">
        <div class="content">
            <h2>Datos del empleados &raquo; Editar datos</h2>
            <hr />
            
            <?php
            $nik = mysqli_real_escape_string($con, (strip_tags($_GET["nik"], ENT_QUOTES)));
            $sql = mysqli_query($con, "SELECT * FROM empleados WHERE codigo='$nik'");
            if (mysqli_num_rows($sql) == 0) {
                header("Location: index.php");
            } else {
                $row = mysqli_fetch_assoc($sql);
            }
            if (isset($_POST['save'])) {
                $codigo           = mysqli_real_escape_string($con, (strip_tags($_POST["codigo"], ENT_QUOTES)));
                $nombres          = mysqli_real_escape_string($con, (strip_tags($_POST["nombres"], ENT_QUOTES)));
                $lugar_nacimiento = mysqli_real_escape_string($con, (strip_tags($_POST["lugar_nacimiento"], ENT_QUOTES)));
                $fecha_nacimiento = mysqli_real_escape_string($con, (strip_tags($_POST["fecha_nacimiento"], ENT_QUOTES)));
                $direccion        = mysqli_real_escape_string($con, (strip_tags($_POST["direccion"], ENT_QUOTES)));
                $telefono         = mysqli_real_escape_string($con, (strip_tags($_POST["telefono"], ENT_QUOTES)));
                $puesto           = mysqli_real_escape_string($con, (strip_tags($_POST["puesto"], ENT_QUOTES)));
                $estado           = mysqli_real_escape_string($con, (strip_tags($_POST["estado"], ENT_QUOTES)));
                $email            = mysqli_real_escape_string($con, (strip_tags($_POST["email"], ENT_QUOTES)));

                // Si se proporciona una nueva contraseña, la actualizamos con hash
                if (!empty($_POST["password"])) {
                    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
                    $update = mysqli_query($con, "UPDATE empleados SET nombres='$nombres', lugar_nacimiento='$lugar_nacimiento', fecha_nacimiento='$fecha_nacimiento', direccion='$direccion', telefono='$telefono', puesto='$puesto', estado='$estado', email='$email', password='$password' WHERE codigo='$nik'") or die(mysqli_error($con));
                } else {
                    $update = mysqli_query($con, "UPDATE empleados SET nombres='$nombres', lugar_nacimiento='$lugar_nacimiento', fecha_nacimiento='$fecha_nacimiento', direccion='$direccion', telefono='$telefono', puesto='$puesto', estado='$estado', email='$email' WHERE codigo='$nik'") or die(mysqli_error($con));
                }

                if ($update) {
                    header("Location: edit.php?nik=" . $nik . "&pesan=sukses");
                } else {
                    echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Error, no se pudo guardar los datos.</div>';
                }
            }
            
            if (isset($_GET['pesan']) == 'sukses') {
                echo '<div class="alert alert-success alert-dismissable d-flex align-items-center"><button type="button" class="btn btn-outline-success me-2" data-dismiss="alert" aria-hidden="true">&times;</button>Los datos han sido guardados con éxito.</div>';
            }
            ?>
            <form class="form-horizontal" action="" method="post">
                <div class="form-group">
                    <label class="col-sm-3 control-label">Index código:</label>
                    <div class="col-sm-2">
                        <input type="text" name="codigo" value="<?php echo $row['codigo']; ?>" class="form-control" placeholder="" required>
                    </div><br>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Nombre completo:</label>
                    <div class="col-sm-4">
                        <input type="text" name="nombres" value="<?php echo $row['nombres']; ?>" class="form-control" placeholder="" required>
                    </div><br>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Lugar de nacimiento:</label>
                    <div class="col-sm-4">
                        <input type="text" name="lugar_nacimiento" value="<?php echo $row['lugar_nacimiento']; ?>" class="form-control" placeholder="" required>
                    </div><br>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Fecha de nacimiento:</label>
                    <div class="col-sm-4">
                        <input type="text" name="fecha_nacimiento" value="<?php echo $row['fecha_nacimiento']; ?>" class="form-control datepicker" placeholder="" readonly required>
                    </div><br>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Dirección:</label>
                    <div class="col-sm-3">
                        <textarea name="direccion" class="form-control" placeholder="Dirección"><?php echo $row['direccion']; ?></textarea>
                    </div><br>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Teléfono:</label>
                    <div class="col-sm-3">
                        <input type="text" name="telefono" value="<?php echo $row['telefono']; ?>" class="form-control" placeholder="" required>
                    </div><br>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Puesto:</label>
                    <div class="col-sm-3">
                        <input type="text" name="puesto" value="<?php echo $row['puesto']; ?>" class="form-control" placeholder="" required>
                    </div><br>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Estado:</label>
                    <div class="col-sm-3">
                        <select name="estado" class="form-control">
                            <option value="">- Selecciona estado -</option>
                            <option value="1" <?php if ($row['estado'] == 1) echo "selected"; ?>>Fijo</option>
                            <option value="2" <?php if ($row['estado'] == 2) echo "selected"; ?>>Contratado</option>
                            <option value="3" <?php if ($row['estado'] == 3) echo "selected"; ?>>Outsourcing</option>
                        </select>
                    </div><br>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Email:</label>
                    <div class="col-sm-4">
                        <input type="email" name="email" value="<?php echo $row['email']; ?>" class="form-control" placeholder="" required>
                    </div><br>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Nueva Contraseña:</label>
                    <div class="col-sm-4">
                        <input type="password" name="password" class="form-control" placeholder="Dejar en blanco para no cambiar">
                    </div><br>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">&nbsp;</label>
                    <div class="col-sm-6" style="margin-bottom: 100px;">
                        <input type="submit" name="save" class="btn btn-sm btn-primary" value="Guardar datos">
                        <a href="index.php" class="btn btn-sm btn-danger">Cancelar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
 
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script>
		$(document).ready(function() {
			$('.datepicker').datepicker({
				format: 'yyyy-m-dd',
				autoclose: true,
				todayHighlight: true
			});
		});
	</script>
</body>
</html>
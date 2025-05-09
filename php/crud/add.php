<?php
include("conexion.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Latihan MySQLi</title>
 
	<!-- Bootstrap -->
	<link href="../../css/bootstrap.css" rel="stylesheet">
	<link href="../../css/bootstrap-datepicker.min.css" rel="stylesheet">
	<!-- Bootstrap Datepicker CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">
	<style>
		.content {
			margin-top: 80px;
		}
	</style>
 
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<?php include("nav.php");?>
	<div class="container">
		<div class="content">
			<h2>Datos del empleados &raquo; Agregar datos</h2>
			<hr />
 
			<?php
			if (isset($_POST['add'])) {
				$nombres             = mysqli_real_escape_string($con, (strip_tags($_POST["nombres"], ENT_QUOTES)));
				$lugar_nacimiento    = mysqli_real_escape_string($con, (strip_tags($_POST["lugar_nacimiento"], ENT_QUOTES)));
				$fecha_nacimiento    = mysqli_real_escape_string($con, (strip_tags($_POST["fecha_nacimiento"], ENT_QUOTES)));
				$direccion           = mysqli_real_escape_string($con, (strip_tags($_POST["direccion"], ENT_QUOTES)));
				$telefono            = mysqli_real_escape_string($con, (strip_tags($_POST["telefono"], ENT_QUOTES)));
				$puesto              = mysqli_real_escape_string($con, (strip_tags($_POST["puesto"], ENT_QUOTES)));
				$estado              = mysqli_real_escape_string($con, (strip_tags($_POST["estado"], ENT_QUOTES)));
				$email               = mysqli_real_escape_string($con, (strip_tags($_POST["email"], ENT_QUOTES)));
				$password            = password_hash($_POST["password"], PASSWORD_BCRYPT);
			
				$insert = mysqli_query($con, "INSERT INTO empleados(nombres, lugar_nacimiento, fecha_nacimiento, direccion, telefono, puesto, estado, password, email)
											  VALUES('$nombres', '$lugar_nacimiento', '$fecha_nacimiento', '$direccion', '$telefono', '$puesto', '$estado', '$password', '$email')") or die(mysqli_error($con));
				if($insert){
					echo '<div class="alert alert-success alert-dismissable d-flex align-items-center"><button type="button" class="btn btn-outline-success me-2" data-dismiss="alert" aria-hidden="true">&times;</button><span>Bien hecho! Los datos han sido guardados con éxito.</span></div>';
				}else{
					echo '<div class="alert alert-danger alert-dismissable d-flex align-items-center"><button type="button" class="btn btn-outline-danger me-2" data-dismiss="alert" aria-hidden="true">&times;</button>Error. No se pudieron enviar los datos.</div>';
				}
			}
			?>
 
			<form class="form-horizontal" action="" method="post">
				<div class="form-group">
					<label class="col-sm-3 control-label">Email:</label>
					<div class="col-sm-4">
						<input type="email" name="email" class="form-control" placeholder="" required>
					</div><br>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Contraseña:</label>
					<div class="col-sm-4">
						<input type="password" name="password" class="form-control" placeholder="" required>
					</div><br>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Nombre completo:</label>
					<div class="col-sm-4">
						<input type="text" name="nombres" class="form-control" placeholder="" required>
					</div><br>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Lugar de nacimiento:</label>
					<div class="col-sm-4">
						<input type="text" name="lugar_nacimiento" class="form-control" placeholder="" required>
					</div><br>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Fecha de nacimiento:</label>
					<div class="col-sm-4">
						<input type="text" name="fecha_nacimiento" class="form-control datepicker" readonly required>
					</div><br>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Dirección:</label>
					<div class="col-sm-3">
						<textarea name="direccion" class="form-control" placeholder=""></textarea>
					</div><br>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Teléfono:</label>
					<div class="col-sm-3">
						<input type="text" name="telefono" class="form-control" placeholder="" required>
					</div><br>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Puesto:</label>
					<div class="col-sm-3">
						<input type="text" name="puesto" class="form-control" placeholder="" required>
					</div><br>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Estado:</label>
					<div class="col-sm-3">
						<select name="estado" class="form-control">
							<option value=""> ----- </option>
                           <option value="1">Fijo</option>
							<option value="2">Contratado</option>
							
							 <option value="3">Outsourcing</option>
						</select>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-3 control-label">&nbsp;</label>
					<div class="col-sm-6" style="margin-bottom: 100px;">
						<input type="submit" name="add" class="btn btn-sm btn-primary" value="Guardar datos">
						<a href="index.php" class="btn btn-sm btn-danger">Cancelar</a>
					</div>
				</div>
			</form>
		</div>
	</div>
 
	<!-- jQuery -->
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

	<!-- Bootstrap Bundle JS -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

	<!-- Bootstrap Datepicker JS -->
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
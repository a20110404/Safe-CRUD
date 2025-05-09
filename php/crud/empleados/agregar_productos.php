<?php
include("../conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_producto = mysqli_real_escape_string($con, $_POST['nombre_producto']);
    $descripcion = mysqli_real_escape_string($con, $_POST['descripcion']);
    $precio = mysqli_real_escape_string($con, $_POST['precio']);
    $cantidad = mysqli_real_escape_string($con, $_POST['cantidad']);

    $query = "INSERT INTO productos (nombre_producto, descripcion, precio, cantidad) 
              VALUES ('$nombre_producto', '$descripcion', '$precio', '$cantidad')";

    if (mysqli_query($con, $query)) {
        $mensaje = "Producto agregado correctamente.";
    } else {
        $mensaje = "Error al agregar el producto: " . mysqli_error($con);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Agregar Productos</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include("nav_empleados.php"); ?>
    <div class="container">
        <div class="content">
            <h2>Agregar Producto</h2>
            <hr />

            <?php if (isset($mensaje)): ?>
                <div class="alert alert-info">
                    <?= $mensaje ?>
                </div>
            <?php endif; ?>

            <form method="post" action="agregar_productos.php">
                <div class="mb-3">
                    <label for="nombre_producto" class="form-label">Nombre del Producto</label>
                    <input type="text" class="form-control" id="nombre_producto" name="nombre_producto" required>
                </div>
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="precio" class="form-label">Precio</label>
                    <input type="number" step="0.01" class="form-control" id="precio" name="precio" required>
                </div>
                <div class="mb-3">
                    <label for="cantidad" class="form-label">Cantidad</label>
                    <input type="number" class="form-control" id="cantidad" name="cantidad" required>
                </div>
                <button type="submit" class="btn btn-primary">Agregar Producto</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
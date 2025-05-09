<?php
include("../conexion.php");

// Verificar si se recibió el ID del producto
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($con, (strip_tags($_GET["id"], ENT_QUOTES)));

    // Obtener los datos del producto
    $query = mysqli_query($con, "SELECT * FROM productos WHERE id='$id'");
    if (mysqli_num_rows($query) == 0) {
        echo '<div class="alert alert-warning">No se encontró el producto.</div>';
        exit();
    } else {
        $row = mysqli_fetch_assoc($query);
    }
}

// Actualizar el producto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = mysqli_real_escape_string($con, $_POST['id']);
    $nombre_producto = mysqli_real_escape_string($con, $_POST['nombre_producto']);
    $descripcion = mysqli_real_escape_string($con, $_POST['descripcion']);
    $precio = mysqli_real_escape_string($con, $_POST['precio']);
    $cantidad = mysqli_real_escape_string($con, $_POST['cantidad']);

    $update = mysqli_query($con, "UPDATE productos SET 
        nombre_producto='$nombre_producto', 
        descripcion='$descripcion', 
        precio='$precio', 
        cantidad='$cantidad' 
        WHERE id='$id'") or die(mysqli_error($con));

    if ($update) {
        header("Location: index_empleados.php?mensaje=producto_actualizado");
        exit();
    } else {
        $mensaje = "Error al actualizar el producto.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modificar Producto</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include("nav_empleados.php"); ?>
    <div class="container">
        <div class="content">
            <h2>Modificar Producto</h2>
            <hr />

            <?php if (isset($mensaje)): ?>
                <div class="alert alert-danger">
                    <?= $mensaje ?>
                </div>
            <?php endif; ?>

            <form method="post" action="modificar_productos.php?id=<?= $id ?>">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <div class="mb-3">
                    <label for="nombre_producto" class="form-label">Nombre del Producto</label>
                    <input type="text" class="form-control" id="nombre_producto" name="nombre_producto" value="<?= $row['nombre_producto'] ?>" required>
                </div>
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required><?= $row['descripcion'] ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="precio" class="form-label">Precio</label>
                    <input type="number" step="0.01" class="form-control" id="precio" name="precio" value="<?= $row['precio'] ?>" required>
                </div>
                <div class="mb-3">
                    <label for="cantidad" class="form-label">Cantidad</label>
                    <input type="number" class="form-control" id="cantidad" name="cantidad" value="<?= $row['cantidad'] ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Actualizar Producto</button>
                <a href="index_empleados.php" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
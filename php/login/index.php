<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'database.php';
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: ../crud/index.php');
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = htmlspecialchars(trim($_POST['email']));
    $password = trim($_POST['pass_word']);

    // Verificar en la tabla users
    $stmt = $conn->prepare("SELECT id, firstName, pass_word FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['pass_word'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['firstName'];
            header('Location: ../crud/index.php');
            exit();
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        // Si no existe en users, verificar en la tabla empleados
        $stmt = $conn->prepare("SELECT codigo, nombres, password FROM empleados WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $empleado = $result->fetch_assoc();
            if (password_verify($password, $empleado['password'])) {
                $_SESSION['user_id'] = $empleado['codigo'];
                $_SESSION['user_name'] = $empleado['nombres'];
                header('Location: ../crud/empleados/index_empleados.php');
                exit();
            } else {
                $error = "Contraseña incorrecta.";
            }
        } else {
            $error = "Usuario no encontrado.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Iniciar Sesión</h2>
        
        <?php if (!empty($error)): ?>
            <div class="alert error">
                <p><?= $error ?></p>
            </div>
        <?php endif; ?>

        <form method="post" action="index.php">
            <input type="email" name="email" placeholder="Correo electrónico" required>
            <input type="password" name="pass_word" placeholder="Contraseña" required>
            <button type="submit">Ingresar</button>
        </form>
        
        <p>¿No tienes cuenta? <a href="register.php">Regístrate aquí</a></p>
    </div>
</body>
</html>
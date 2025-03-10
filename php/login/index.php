<?php
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

    $stmt = $conn->prepare("SELECT firstName, pass_word FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['pass_word'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['firstName'];
            header('Location: ../crud/index.php');
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error =  "Usuario no encontrado.";
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
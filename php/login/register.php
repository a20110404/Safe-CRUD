<?php
// register.php
include 'database.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $firstName = trim($_POST['firstName']);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $current_date = date('Y-m-d H:i:s');


    // Validaciones
    if (empty($firstName)) $errors[] = "El firstName es requerido";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email inválido";
    if (strlen($password) < 8) $errors[] = "La contraseña debe tener al menos 8 caracteres";
    if (!preg_match("/[A-Z]/", $password)) $errors[] = "La contraseña debe contener al menos una mayúscula";
    if (!preg_match("/[0-9]/", $password)) $errors[] = "La contraseña debe contener al menos un número";
    if ($password !== $confirm_password) $errors[] = "Las contraseñas no coinciden";

    if (empty($errors)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("INSERT INTO users (firstName, email, pass_word, dateRegistry) 
        VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $firstName, $email, $password_hash, $current_date);
        if ($stmt->execute()) {
            header('Location: index.php?registro=exito');
            exit();
        } else {
            $errors[] = "Error al registrar: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Registro</title>
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Registro de Usuario</h2>
        
        <?php if (!empty($errors)): ?>
            <div class="alert error">
                <?php foreach ($errors as $error): ?>
                    <p><?= $error ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post" action="register.php">
            <input type="text" name="firstName" placeholder="Nombre completo" 
                   value="<?= htmlspecialchars($_POST['firstName'] ?? '') ?>" required>
            
            <input type="email" name="email" placeholder="Correo electrónico"
                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            
            <input type="password" name="password" placeholder="Contraseña" required>
            <input type="password" name="confirm_password" placeholder="Confirmar contraseña" required>
            
            <button type="submit">Registrarse</button>
        </form>
        
        <p>¿Ya tienes cuenta? <a href="index.php">Inicia sesión</a></p>
    </div>
</body>
</html>
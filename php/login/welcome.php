<?php
session_start();


echo "<h1>Bienvenido, " . htmlspecialchars($_SESSION['user_name']) . "</h1>";
echo '<a href="logout.php">Cerrar sesión</a>';
?>

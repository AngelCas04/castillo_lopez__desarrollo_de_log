<?php
session_start();

$user = $_POST['txt_user'] ?? '';
$pass = $_POST['txt_pass'] ?? '';

if ($user === 'admin' && $pass === '1234') {
    $_SESSION['usuario'] = $user;
    echo "<script>alert('Bienvenido, $user'); location.href='../dashboard.php';</script>";
} else {
    echo "<script>alert('Usuario o contraseña incorrectos'); location.href='../index.php';</script>";
}
?>

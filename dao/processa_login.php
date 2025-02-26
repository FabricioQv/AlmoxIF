<?php
session_start();
require_once "UsuarioDAO.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $login = trim($_POST["login"]);
    $senha = trim($_POST["senha"]);

    $usuarioDAO = new UsuarioDAO();
    $usuario = $usuarioDAO->login($login, $senha);

    if ($usuario) {
        $_SESSION["usuario"] = $usuario;
        header("Location: ../views/dashboard.php");
        exit();
    } else {
        header("Location: ../views/login.php?erro=1");
        exit();
    }
}
?>

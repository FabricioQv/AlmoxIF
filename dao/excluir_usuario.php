<?php
session_start();
if (!isset($_SESSION["usuario"]) || $_SESSION["usuario"]["fk_Role_id_role"] != 1) {
    header("Location: ../views/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id_usuario"])) {
    $id = intval($_POST["id_usuario"]);

    // Impede o usuário de excluir a si mesmo
    if ($_SESSION["usuario"]["id_usuario"] == $id) {
        header("Location: ../views/usuarios.php");
        exit();
    }

    require_once "UsuarioDAO.php";
    $dao = new UsuarioDAO();
    $dao->excluirUsuario($id);
}

header("Location: ../views/usuarios.php");
exit();

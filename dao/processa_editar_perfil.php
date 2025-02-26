<?php
session_start();
require_once "UsuarioDAO.php";

if (!isset($_SESSION["usuario"])) {
    header("Location: ../views/login.php");
    exit();
}

$usuario = $_SESSION["usuario"];
$usuarioDAO = new UsuarioDAO();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $siape = trim($_POST["siape"]);
    $senha_atual = trim($_POST["senha_atual"]);
    $nova_senha = trim($_POST["nova_senha"]);
    $confirmar_senha = trim($_POST["confirmar_senha"]);

    // Verifica se a senha atual está correta
    if (!$usuarioDAO->verificarSenha($usuario["id_usuario"], $senha_atual)) {
        header("Location: ../views/editar_perfil.php?erro=senha");
        exit();
    }

    // Atualiza o SIAPE
    $atualizou = $usuarioDAO->atualizarPerfil($usuario["id_usuario"], $siape, $nova_senha, $confirmar_senha);

    if ($atualizou) {
        $_SESSION["usuario"]["siape"] = $siape; // Atualiza a sessão com o novo SIAPE
        header("Location: ../views/editar_perfil.php?sucesso=1");
        exit();
    } else {
        header("Location: ../views/editar_perfil.php?erro=1");
        exit();
    }
}
?>

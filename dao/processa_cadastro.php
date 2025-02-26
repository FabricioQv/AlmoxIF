<?php
require_once "Database.php";
require_once "../classes/Usuario.php";
require_once "UsuarioDAO.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST["nome"]);
    $siape = trim($_POST["siape"]) ?: null;
    $login = trim($_POST["login"]);
    $senha = trim($_POST["senha"]);
    $role = intval($_POST["role"]);

    if (!empty($nome) && !empty($login) && !empty($senha) && !empty($role)) {
        $usuarioDAO = new UsuarioDAO();

        if ($usuarioDAO->existeLogin($login)) {
            header("Location: ../views/cadastro_usuario.php?duplicado=1");
            exit();
        }

        // Prossegue com o cadastro
        $usuario = new Usuario();
        $usuario->setNome($nome);
        $usuario->setSiape($siape);
        $usuario->setLogin($login);
        $usuario->setSenha($senha);
        $usuario->setRoleId($role);

        if ($usuarioDAO->registrarUsuario($usuario)) {
            header("Location: ../views/cadastro_usuario.php?sucesso=1");
            exit();
        } else {
            header("Location: ../views/cadastro_usuario.php?erro=1");
            exit();
        }
    } else {
        header("Location: ../views/cadastro_usuario.php?erro=1");
        exit();
    }
}
?>

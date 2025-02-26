<?php
session_start();
require_once "ItemDAO.php";

if (!isset($_SESSION["usuario"])) {
    header("Location: ../views/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST["nome"]);
    $codigo = trim($_POST["codigo"]);
    $quantidade = intval($_POST["quantidade"]);
    $validade = !empty($_POST["validade"]) ? $_POST["validade"] : null;
    $categoria = intval($_POST["categoria"]);
    
    $imagemNome = null;
    
    if (!empty($_FILES["imagem"]["name"])) {
        $extensao = pathinfo($_FILES["imagem"]["name"], PATHINFO_EXTENSION);
        $imagemNome = uniqid("item_") . "." . $extensao;
        move_uploaded_file($_FILES["imagem"]["tmp_name"], "../uploads/" . $imagemNome);
    }

    $itemDAO = new ItemDAO();

    if ($itemDAO->verificarCodigoExistente($codigo)) {
        header("Location: ../views/cadastro_item.php?erro=codigo");
        exit();
    }

    $sucesso = $itemDAO->cadastrarItem($nome, $codigo, $quantidade, $validade, $categoria, $imagemNome);

    if ($sucesso) {
        header("Location: ../views/cadastro_item.php?sucesso=1");
        exit();
    } else {
        header("Location: ../views/cadastro_item.php?erro=1");
        exit();
    }
}
?>

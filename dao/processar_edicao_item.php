<?php
session_start();
require_once "ItemDAO.php";

if (!isset($_SESSION["usuario"])) {
    header("Location: ../views/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_item = intval($_POST["id_item"]);
    $nome = trim($_POST["nome"]);
    $codigo = trim($_POST["codigo"]);
    $estoqueCritico = !empty($_POST["estoqueCritico"]) ? intval($_POST["estoqueCritico"]) : null;

    // Verifica se foi enviada uma nova imagem
    $imagemNome = null;
    if (!empty($_FILES["imagem"]["name"])) {
        $extensao = strtolower(pathinfo($_FILES["imagem"]["name"], PATHINFO_EXTENSION));
        $extensoesPermitidas = ["jpg", "jpeg", "png", "gif", "webp"];

        if (in_array($extensao, $extensoesPermitidas)) {
            $imagemNome = uniqid("item_") . "." . $extensao;
            move_uploaded_file($_FILES["imagem"]["tmp_name"], "../uploads/" . $imagemNome);
        } else {
            header("Location: ../views/editar_item.php?id=$id_item&erro=imagem");
            exit();
        }
    }

    $itemDAO = new ItemDAO();
    $sucesso = $itemDAO->atualizarItem($id_item, $nome, $codigo, $estoqueCritico, $imagemNome);

    if ($sucesso) {
        header("Location: ../views/estoque.php?sucesso=editado");
        exit();
    } else {
        header("Location: ../views/editar_item.php?id=$id_item&erro=1");
        exit();
    }
}

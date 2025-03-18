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
    $categoria = intval($_POST["categoria"]);
    $unidade = trim($_POST["unidade"]);

    // Definição de valores opcionais
    $estoqueCritico = isset($_POST["estoqueCritico"]) && $_POST["estoqueCritico"] !== "" ? intval($_POST["estoqueCritico"]) : null;
    $validade = !empty($_POST["validade"]) ? $_POST["validade"] : null;
    $imagemNome = null;

    // Verifica se uma imagem foi enviada
    if (!empty($_FILES["imagem"]["name"])) {
        $extensao = strtolower(pathinfo($_FILES["imagem"]["name"], PATHINFO_EXTENSION));
        $extensoesPermitidas = ["jpg", "jpeg", "png", "gif", "webp"];

        if (in_array($extensao, $extensoesPermitidas)) {
            $imagemNome = uniqid("item_") . "." . $extensao;
            move_uploaded_file($_FILES["imagem"]["tmp_name"], "../uploads/" . $imagemNome);
        } else {
            header("Location: ../views/cadastro_item.php?erro=imagem");
            exit();
        }
    }

    $itemDAO = new ItemDAO();

    // Verificar se o código do item já existe
    if ($itemDAO->verificarCodigoExistente($codigo)) {
        header("Location: ../views/cadastro_item.php?erro=codigo");
        exit();
    }

    // Cadastrar o item no banco de dados
    $sucesso = $itemDAO->cadastrarItem($nome, $codigo, $categoria, $estoqueCritico, $quantidade, $validade, $imagemNome, $unidade );

    if ($sucesso) {
        header("Location: ../views/cadastro_item.php?sucesso=1");
        exit();
    } else {
        header("Location: ../views/cadastro_item.php?erro=1");
        exit();
    }
}
?>

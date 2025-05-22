<?php
require_once "../dao/CategoriaDAO.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST["id"] ?? null;
    $nome = trim($_POST["nome"] ?? "");

    if ($id && $nome) {
        $dao = new CategoriaDAO();
        $dao->atualizarNome($id, $nome);
        echo "OK";
    } else {
        http_response_code(400);
        echo "Dados inválidos";
    }
}
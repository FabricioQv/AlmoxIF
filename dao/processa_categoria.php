<?php
require_once "../dao/CategoriaDAO.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nome'])) {
    $nome = trim($_POST['nome']);

    if (!empty($nome)) {
        $categoriaDAO = new CategoriaDAO();
        $categoriaDAO->criarCategoria($nome);
    }
}

header("Location: ../views/cadastro_categoria.php");
exit;

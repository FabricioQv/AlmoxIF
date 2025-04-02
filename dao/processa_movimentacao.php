<?php
session_start();
require_once "../dao/Database.php";
require_once "../classes/Movimento.php";
require_once "../dao/MovimentoDAO.php";
require_once "../dao/ItemDAO.php";

if (!isset($_SESSION["usuario"])) {
    header("Location: ../views/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $itemDAO = new ItemDAO();
    $itemId = intval($_POST["item"]);
    $usuarioId = $_SESSION["usuario"]["id_usuario"]; // Obtendo o usuário logado
    $tipo = $_POST["tipo"];
    $quantidade = intval($_POST["quantidade"]);
    $validade = !empty($_POST["validade"]) ? $_POST["validade"] : null;
    $observacao = !empty($_POST["observacao"]) ? trim($_POST["observacao"]) : null;

    if ($quantidade <= 0) {
        header("Location: ../views/movimentacao.php?erro=quantidade");
        exit();
    }

    try {
        $movimento = new Movimento($itemId, $usuarioId, $tipo, $quantidade, $validade, $observacao);
        $dao = new MovimentoDAO();
        
        if ($tipo === "entrada") {
            $sucesso = $dao->registrarMovimento($movimento);
        } elseif ($tipo === "saida") {
            $item = $itemDAO->buscarPorId($itemId); // precisa criar esse método se não tiver ainda
            $estoqueAtual = $item['estoque_atual'];

            if ($quantidade > $estoqueAtual) {
                header("Location: ../views/movimentacao.php?erro=estoqueinsuficiente");
                exit();
            }

    $sucesso = $dao->removerItemFIFO($itemId, $quantidade, $observacao);
            $sucesso = $dao->removerItemFIFO($itemId, $quantidade, $observacao);
        }
        
        if ($sucesso) {
            header("Location: ../views/movimentacao.php?sucesso=1");
        } else {
            header("Location: ../views/movimentacao.php?erro=1");
        }
        exit();
    } catch (Exception $e) {
        header("Location: ../views/movimentacao.php?erro=1");
        exit();
    }
}

<?php
session_start();
require_once "../dao/ItemDAO.php";

if (!isset($_SESSION["usuario"])) {
    header("Location: ../views/login.php");
    exit();
}

$itemDAO = new ItemDAO();
$itens = $itemDAO->listarItensComEstoque(); // Método novo que você pode criar para obter código, nome e estoque

$arquivo = "relatorio_material_consumo_" . date("Ymd_His") . ".csv";

// Cabeçalhos para download
header("Content-Type: text/csv; charset=UTF-8");
header("Content-Disposition: attachment; filename=$arquivo");

// Gera o CSV
$saida = fopen("php://output", "w");

// Cabeçalho do relatório
fputcsv($saida, ["Código", "Material de Consumo", "Estoque", "Fichas", "Físico", "Observações"], ";");

// Linhas do relatório
foreach ($itens as $item) {
    fputcsv($saida, [
        $item["codigo"],
        $item["nome"],
        $item["estoque"],
        "", "", "" // Campos extras em branco para preenchimento manual
    ], ";");
}

fclose($saida);
exit();

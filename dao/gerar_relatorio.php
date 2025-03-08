<?php
session_start();
require_once "../dao/MovimentoDAO.php";

if (!isset($_SESSION["usuario"])) {
    header("Location: ../views/login.php");
    exit();
}

// Definir datas do formulário
$dataInicio = isset($_POST["dataInicio"]) ? $_POST["dataInicio"] : date("Y-m-d");
$dataFim = isset($_POST["dataFim"]) ? $_POST["dataFim"] : date("Y-m-d");




$movimentoDAO = new MovimentoDAO();
$dados = $movimentoDAO->gerarRelatorioMovimentacao($dataInicio, $dataFim);


$arquivo = "relatorio_estoque_" . date("Ymd_His") . ".csv";

// Configurar cabeçalhos para download
header("Content-Type: text/csv; charset=UTF-8");
header("Content-Disposition: attachment; filename=$arquivo");

// Criar o arquivo CSV
$saida = fopen("php://output", "w");

// Escrever o cabeçalho do CSV
fputcsv($saida, ["Item", "Estoque Inicial", "Entradas", "Saídas", "Estoque Final"], ";");

// Escrever os dados corretamente, substituindo valores nulos
foreach ($dados as $linha) {
    fputcsv($saida, [
        $linha["item_nome"],
        !empty($linha["estoque_inicial"]) ? $linha["estoque_inicial"] : 0,
        !empty($linha["total_entrada"]) ? $linha["total_entrada"] : 0,
        !empty($linha["total_saida"]) ? $linha["total_saida"] : 0,
        !empty($linha["estoque_final"]) ? $linha["estoque_final"] : 0
    ], ";");
}


fclose($saida);
exit();
?>

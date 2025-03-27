<?php
session_start();
require_once "../dao/Database.php";
require_once "../dao/MovimentoDAO.php";
require_once "../classes/Movimento.php";

if (!isset($_SESSION["usuario"])) {
    header("Location: ../views/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["arquivo_pdf"])) {
    $arquivoTmp = $_FILES["arquivo_pdf"]["tmp_name"];
    $tipoMovimentacao = $_POST["tipo_movimentacao"] ?? "entrada"; // Entrada ou saída

    // Abrir o PDF como texto
    $conteudo = file_get_contents($arquivoTmp);
    if (!$conteudo) {
        echo "Erro: Não foi possível ler o conteúdo do PDF.";
        exit();
    }

    // Debug: Mostrar conteúdo bruto do PDF
    echo "<pre>Conteúdo do PDF:\n" . htmlspecialchars($conteudo) . "</pre>";

    // Expressão regular para capturar códigos de 6 dígitos e quantidades associadas
    preg_match_all('/(\d{6})[^\d]+(\d{1,3})/', $conteudo, $matches, PREG_SET_ORDER);
    
    // Debug: Mostrar os matches encontrados
    echo "<pre>Matches encontrados:\n" . print_r($matches, true) . "</pre>";

    if (empty($matches)) {
        echo "Erro: Nenhuma correspondência encontrada para códigos e quantidades.";
        exit();
    }

    $movimentoDAO = new MovimentoDAO();
    $usuarioId = $_SESSION["usuario"]["id_usuario"];

    foreach ($matches as $match) {
        $codigoItem = trim($match[1]);
        $quantidade = intval($match[2]);

        echo "<pre>Processando Item: Código = $codigoItem, Quantidade = $quantidade</pre>";
        
        $item = $movimentoDAO->buscarItemPorCodigo($codigoItem);
        if (!$item) {
            echo "<pre>Item não encontrado no banco: $codigoItem</pre>";
            continue; // Pula se o item não for encontrado no sistema
        }

        $movimento = new Movimento($item['id_item'], $usuarioId, $tipoMovimentacao, $quantidade, null, "Processado via PDF");
        $movimentoDAO->registrarMovimento($movimento);
    }

    echo "<pre>Processamento concluído com sucesso!</pre>";
    exit();
} else {
    echo "Erro: Falha no upload do arquivo.";
    exit();
}
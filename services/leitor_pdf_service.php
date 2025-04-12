<?php
require '../vendor/autoload.php';
require_once '../dao/ItemDAO.php';

use Smalot\PdfParser\Parser;

function processarPDF($arquivoTemporario) {
    $parser = new Parser();
    $pdf = $parser->parseFile($arquivoTemporario);
    $text = $pdf->getText();

    $linhas = explode("\n", $text);
    $itensEncontrados = [];
    $itemDAO = new ItemDAO();

    function sanitizarCodigo($codigoBruto, $itemDAO) {
        if ($itemDAO->buscarPorCodigo($codigoBruto)) {
            return $codigoBruto;
        }

        $codigoSemZeros = ltrim($codigoBruto, '0');
        if ($itemDAO->buscarPorCodigo($codigoSemZeros)) {
            return $codigoSemZeros;
        }

        return $codigoBruto;
    }

    $modoCaptura = false;
    $codigo = '';
    $descricao = '';
    $quantidade = '';

    foreach ($linhas as $linha) {
        $linha = trim($linha);

        // Detecta início de um item
        if (preg_match('/^\d+\s+\d+\s+(\d+)/', $linha, $matches)) {
            // Se já estávamos capturando um item anterior, salva ele
            if (!empty($codigo) && !empty($quantidade)) {
                $codigoFinal = sanitizarCodigo($codigo, $itemDAO);
                $item = $itemDAO->buscarPorCodigo($codigoFinal);

                if ($item) {
                    $itensEncontrados[] = [
                        'codigo_pdf' => $codigo,
                        'codigo' => $codigoFinal,
                        'nome' => trim($descricao),
                        'quantidade' => (int) $quantidade
                    ];
                }
            }

            // Inicia captura de novo item
            $codigo = $matches[1];
            $descricao = '';
            $quantidade = '';
            $modoCaptura = 'descricao';
            continue;
        }

        // Captura descrição ou quantidade
        if ($modoCaptura === 'descricao') {
            if (preg_match('/^\d+\s+[\d,.]+$/', $linha)) {
                $partes = preg_split('/\s+/', $linha);
                $quantidade = $partes[0];
                $modoCaptura = false;
            } else {
                $descricao .= ' ' . $linha;
            }
        }
    }

    // Verifica se o último item foi capturado
    if (!empty($codigo) && !empty($quantidade)) {
        $codigoFinal = sanitizarCodigo($codigo, $itemDAO);
        $item = $itemDAO->buscarPorCodigo($codigoFinal);

        if ($item) {
            $itensEncontrados[] = [
                'codigo_pdf' => $codigo,
                'codigo' => $codigoFinal,
                'nome' => trim($descricao),
                'quantidade' => (int) $quantidade
            ];
        }
    }

    return $itensEncontrados;
}

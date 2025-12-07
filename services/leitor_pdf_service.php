<?php
require '../vendor/autoload.php';
require_once '../dao/ItemDAO.php';

use Smalot\PdfParser\Parser;

function processarPDF($arquivoTemporario) {
    $parser = new Parser();
    $pdf = $parser->parseFile($arquivoTemporario);
    $text = $pdf->getText();

    $text = str_replace("\t", " ", $text);
    $text = preg_replace('/ {2,}/', ' ', $text);
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

        if (preg_match('/^\d+\s+\d+\s+(\d{5,6})\b(.*)$/', $linha, $matches)) {
            if (!empty($codigo) && !empty($quantidade)) {
                $codigoFinal = sanitizarCodigo($codigo, $itemDAO);
                $item = $itemDAO->buscarPorCodigo($codigoFinal);
                if ($item) {
                    $itensEncontrados[] = [
                        'codigo_pdf' => $codigo,
                        'codigo' => $codigoFinal,
                        'nome' => trim($descricao),
                        'quantidade' => (int)$quantidade
                    ];
                }
            }

            $codigo = $matches[1];
            $descricao = '';
            $quantidade = '';

            $restante = trim($matches[2]);
            if (!empty($restante)) {
                if (preg_match('/^(.*)\s+(\d{1,4})\s+[\d.,]+$/', $restante, $m2)) {
                    $descricao = trim($m2[1]);
                    $quantidade = $m2[2];
                    $modoCaptura = false;
                } else {
                    $descricao = $restante;
                    $modoCaptura = 'descricao';
                }
            } else {
                $modoCaptura = 'descricao';
            }
            continue;
        }

        if ($modoCaptura === 'descricao') {
            if (preg_match('/^(\d{1,4})\s+[\d.,]+$/', $linha, $m)) {
                $quantidade = $m[1];
                $modoCaptura = false;
            } else {
                $descricao .= ' ' . $linha;
            }
        }
    }

    if (!empty($codigo) && !empty($quantidade)) {
        $codigoFinal = sanitizarCodigo($codigo, $itemDAO);
        $item = $itemDAO->buscarPorCodigo($codigoFinal);
        if ($item) {
            $itensEncontrados[] = [
                'codigo_pdf' => $codigo,
                'codigo' => $codigoFinal,
                'nome' => trim($descricao),
                'quantidade' => (int)$quantidade
            ];
        }
    }

    return $itensEncontrados;
}

<?php
session_start();
require_once "ImportacaoDAO.php";

if (!isset($_SESSION["usuario"]) || $_SESSION["usuario"]["fk_Role_id_role"] != 1) {
    header("Location: ../views/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["arquivo"])) {
    $arquivoTmp = $_FILES["arquivo"]["tmp_name"];

    if (($handle = fopen($arquivoTmp, "r")) !== false) {
        $dados_csv = [];
        $cabecalho = fgetcsv($handle, 1000, ","); // Captura a primeira linha como cabeçalho

        $mapa_colunas = [
            "Código" => "codigo",
            "Material Consumo" => "nome",
            "Unidade" => "unidade",
            "Elemento Despesa" => "categoria",
            "Estoque" => "estoque_atual"
        ];

        while (($linha = fgetcsv($handle, 1000, ",")) !== false) {
            $item = [];
            foreach ($mapa_colunas as $coluna_csv => $coluna_bd) {
                $indice = array_search($coluna_csv, $cabecalho);
                if ($indice !== false) {
                    $item[$coluna_bd] = $linha[$indice] ?? null;
                }
            }
            $dados_csv[] = $item;
        }
        fclose($handle);

        $importacaoDAO = new ImportacaoDAO();
        $sucesso = $importacaoDAO->importarDados($dados_csv);

        if ($sucesso) {
            header("Location: ../views/importar_csv.php?sucesso=1");
        } else {
            header("Location: ../views/importar_csv.php?erro=1");
        }
    } else {
        header("Location: ../views/importar_csv.php?erro=1");
    }
}
?>

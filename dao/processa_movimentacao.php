<?php
session_start();
require_once "../dao/Database.php";

if (!isset($_SESSION["usuario"])) {
    header("Location: ../views/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $itemId = intval($_POST["item"]);
    $tipo = $_POST["tipo"];
    $quantidade = intval($_POST["quantidade"]);
    $validade = !empty($_POST["validade"]) ? $_POST["validade"] : null;
    $observacao = !empty($_POST["observacao"]) ? trim($_POST["observacao"]) : null;

    if ($quantidade <= 0) {
        header("Location: ../views/movimentacao.php?erro=quantidade");
        exit();
    }

    try {
        $conn = (new Database())->connect();
        $conn->beginTransaction();

        // Inserir movimentação
        $sqlMov = "INSERT INTO movimentacao (fk_item_id, tipo, quantidade, validade, observacao) 
                   VALUES (:itemId, :tipo, :quantidade, :validade, :observacao)";
        $stmtMov = $conn->prepare($sqlMov);
        $stmtMov->bindParam(":itemId", $itemId);
        $stmtMov->bindParam(":tipo", $tipo);
        $stmtMov->bindParam(":quantidade", $quantidade);
        $stmtMov->bindParam(":observacao", $observacao);

        // Corrigido o tratamento da validade
        if (!empty($validade)) {
            $stmtMov->bindParam(":validade", $validade);
        } else {
            $stmtMov->bindValue(":validade", null, PDO::PARAM_NULL);
        }

        $stmtMov->execute();
        $conn->commit();

        header("Location: ../views/movimentacao.php?sucesso=1");
        exit();
    } catch (Exception $e) {
        $conn->rollBack();
        header("Location: ../views/movimentacao.php?erro=bd&msg=" . urlencode($e->getMessage()));
        exit();
    }
}
?>

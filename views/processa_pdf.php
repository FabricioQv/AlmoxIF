<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}

$sucesso = isset($_GET['sucesso']);
$erro = isset($_GET['erro']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processar PDF - Estoque IFSul</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/styles.css">
</head>
<body>
    <?php include "sidebar.php"; ?>
    <?php include "navbar.php"; ?>

    <div class="main-content">
        <div class="container mt-4">
            <h2><i class="bi bi-file-earmark-arrow-up"></i> Processar Nota de Fornecimento</h2>
            
            <!-- Alertas de Sucesso/Erro -->
            <?php if ($sucesso): ?>
                <div class="alert alert-success">✅ PDF processado com sucesso!</div>
            <?php endif; ?>
            <?php if ($erro): ?>
                <div class="alert alert-danger">❌ Erro ao processar PDF.</div>
            <?php endif; ?>

            <div class="card p-4 mt-3">
                <form action="../dao/processa_pdf.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="arquivo_pdf" class="form-label">Carregar PDF da Nota de Fornecimento</label>
                        <input type="file" class="form-control" id="arquivo_pdf" name="arquivo_pdf" accept="application/pdf" required>
                    </div>

                    <div class="mb-3">
                        <label for="tipo_movimentacao" class="form-label">Tipo de Movimentação</label>
                        <select class="form-control" id="tipo_movimentacao" name="tipo_movimentacao" required>
                            <option value="entrada">Entrada</option>
                            <option value="saida">Saída</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-upload"></i> Processar PDF</button>
                </form>
            </div>

            <div class="mt-3">
                <a href="movimentacao.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Voltar</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

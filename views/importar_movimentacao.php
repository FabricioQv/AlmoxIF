<?php
session_start();
require '../services/leitor_pdf_service.php';

$itensEncontrados = [];
$mensagemSucesso = $_GET['sucesso'] ?? null;
$mensagemErro = $_GET['erro'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['pdf'])) {
    $itensEncontrados = processarPDF($_FILES['pdf']['tmp_name']);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Importar Movimentação - AlmoxIF</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/styles.css">
</head>
<body>
<?php include "sidebar.php"; ?>
<?php include "navbar.php"; ?>

<div class="main-content container mt-4">
    <h2 class="fw-bold text-success mb-4"><i class="bi bi-file-earmark-arrow-up"></i> Importar Movimentação via PDF</h2>

    <?php if ($mensagemSucesso): ?>
        <div class="alert alert-success"><?= htmlspecialchars($mensagemSucesso) ?></div>
    <?php elseif ($mensagemErro): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($mensagemErro) ?></div>
    <?php endif; ?>

    <!-- Formulário de upload do PDF -->
    <form method="POST" enctype="multipart/form-data" class="mb-4">
        <div class="mb-3">
            <label for="pdf" class="form-label">Selecione o arquivo PDF:</label>
            <input type="file" name="pdf" id="pdf" class="form-control" accept="application/pdf" required>
        </div>
        <button type="submit" class="btn btn-success fw-bold">Processar PDF</button>
    </form>

    <!-- Exibe os itens encontrados -->
    <?php if (!empty($itensEncontrados)): ?>
        <form action="../dao/processa_importacao_pdf.php" method="POST">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Código (PDF)</th>
                        <th>Código (Banco)</th>
                        <th>Descrição</th>
                        <th>Quantidade</th>
                        <th>Tipo de Movimentação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($itensEncontrados as $index => $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['codigo_pdf']) ?></td>
                            <td><?= htmlspecialchars($item['codigo']) ?></td>
                            <td><?= htmlspecialchars($item['nome']) ?></td>
                            <td><?= htmlspecialchars($item['quantidade']) ?></td>
                            <td>
                                <select name="movimentacoes[<?= $index ?>][tipo]" class="form-select" required>
                                    <option value="entrada">Entrada</option>
                                    <option value="saida">Saída</option>
                                </select>
                                <input type="hidden" name="movimentacoes[<?= $index ?>][codigo]" value="<?= htmlspecialchars($item['codigo']) ?>">
                                <input type="hidden" name="movimentacoes[<?= $index ?>][quantidade]" value="<?= htmlspecialchars($item['quantidade']) ?>">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit" class="btn btn-success fw-bold">Confirmar Movimentações</button>
        </form>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['usuario']['fk_Role_id_role'] == 3) {
    header("Location: estoque.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios - Estoque IFSul</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../public/styles.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <?php include 'navbar.php'; ?>

    <div class="main-content relatorios-container">
        <h2 class="fw-bold mb-4"><i class="bi bi-file-earmark-text"></i> Relatórios</h2>

        <div class="row g-4">
            <!-- Relatório de movimentação -->
            <div class="col-md-12">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body">
                        <h5 class="card-title fw-semibold"><i class="bi bi-bar-chart-line"></i> Movimentação de Estoque</h5>
                        <p class="text-muted">Gere um relatório detalhado das movimentações de estoque dentro de um intervalo de datas.</p>

                        <form action="../dao/gerar_relatorio.php" method="POST">
                            <div class="row align-items-end">
                                <div class="col-md-4">
                                    <label for="dataInicio" class="form-label">Data Inicial</label>
                                    <input type="date" class="form-control" id="dataInicio" name="dataInicio" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="dataFim" class="form-label">Data Final</label>
                                    <input type="date" class="form-control" id="dataFim" name="dataFim" required>
                                </div>
                                <div class="col-md-4 d-grid">
                                    <button type="submit" class="btn btn-success mt-3">
                                        <i class="bi bi-download"></i> Gerar XLSX
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Relatório de conferência -->
            <div class="col-md-12">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body">
                        <h5 class="card-title fw-semibold"><i class="bi bi-clipboard-data"></i> Relatório de Conferência</h5>
                        <p class="text-muted">Use este relatório para conferência física do estoque. Os campos de fichas, físico e observações podem ser preenchidos manualmente após impressão.</p>

                        <form action="../dao/gerar_relatorio_conferencia.php" method="POST">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-file-earmark-excel"></i> Gerar Conferência (.XLSX)
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

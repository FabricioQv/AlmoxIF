<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios - Estoque IFSul</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../public/styles.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <?php include 'navbar.php'; ?>

    <div class="main-content">
        <h2 class="fw-bold mb-4"><i class="bi bi-file-earmark-text"></i> Gerar Relatórios</h2>

        <div class="card">
            <div class="card-body">
                <form action="../dao/gerar_relatorio.php" method="POST">
                    <div class="row mb-3">
                        <div class="col-md-5">
                            <label for="dataInicio" class="form-label">Data Inicial</label>
                            <input type="date" class="form-control" id="dataInicio" name="dataInicio" required>
                        </div>
                        <div class="col-md-5">
                            <label for="dataFim" class="form-label">Data Final</label>
                            <input type="date" class="form-control" id="dataFim" name="dataFim" required>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-success w-1000"><i class="bi bi-download"></i> Gerar CSV</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

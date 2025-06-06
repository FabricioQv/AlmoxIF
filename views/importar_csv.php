<?php
session_start();
if (!isset($_SESSION["usuario"]) || $_SESSION["usuario"]["fk_Role_id_role"] != 1) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['usuario']['fk_Role_id_role'] == 3) {
    header("Location: estoque.php");
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
    <title>Importar Estoque - Estoque IFSul</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../public/styles.css">
</head>
<body>
    <?php include "sidebar.php"; ?>
    <?php include "navbar.php"; ?>

    <div class="main-content d-flex justify-content-center align-items-center flex-column">
    <div class="text-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-upload fs-3 text-success"></i> Importar Estoque</h2>
        <p class="text-muted">Selecione um arquivo CSV para importar os dados de estoque.</p>
    </div>

    <!-- Toasts -->
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <?php if ($sucesso): ?>
            <div id="sucessoToast" class="toast text-bg-success border-0 show">
                <div class="d-flex">
                    <div class="toast-body">✅ Importação realizada com sucesso!</div>
                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($erro): ?>
            <div id="erroToast" class="toast text-bg-danger border-0 show">
                <div class="d-flex">
                    <div class="toast-body">❌ Erro ao importar o arquivo!</div>
                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Formulário -->
    <div class="card shadow-lg border-0 p-4 rounded-4" style="max-width: 600px; width: 100%;">
        <form action="../dao/processa_importacao.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="arquivo" class="form-label fw-semibold">Arquivo CSV</label>
                <input type="file" class="form-control" id="arquivo" name="arquivo" accept=".csv" required>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-success fw-semibold">
                    <i class="bi bi-file-earmark-arrow-up me-1"></i> Importar Arquivo
                </button>
            </div>
        </form>
    </div>
</div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let toastList = document.querySelectorAll(".toast");
            toastList.forEach(toastEl => {
                let toast = new bootstrap.Toast(toastEl);
                toast.show();
                setTimeout(() => toastEl.remove(), 5000);
            });
        });
    </script>
</body>
</html>

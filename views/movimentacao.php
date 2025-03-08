<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}

require_once "../dao/ItemDAO.php";
$itemDAO = new ItemDAO();

$itens = $itemDAO->listarEstoque();
$sucesso = isset($_GET['sucesso']);
$erro = isset($_GET['erro']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movimentação de Estoque - Estoque IFSul</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../public/styles.css">
</head>
<body>

    <?php include "sidebar.php"; ?> 
    <?php include "navbar.php"; ?>

    <!-- Toast de Sucesso/Erro -->
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="toastSucesso" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false" style="display: none;">
            <div class="d-flex">
                <div class="toast-body">Movimentação registrada com sucesso!</div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
        <div id="toastErro" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false" style="display: none;">
            <div class="d-flex">
                <div class="toast-body">Erro ao registrar movimentação!</div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <!-- Conteúdo Principal -->
    <div class="main-content">
        <div class="profile-card">
            <h2><i class="bi bi-arrow-left-right"></i> Registrar Movimentação</h2>

            <!-- Formulário de Movimentação -->
            <div class="card p-4">
                <form action="../dao/processa_movimentacao.php" method="POST">
                    <div class="mb-3">
                        <label for="item" class="form-label">Selecione o Item</label>
                        <select class="form-control" id="item" name="item" required>
                            <option value="">Escolha um item</option>
                            <?php foreach ($itens as $item): ?>
                                <option value="<?= $item['id_item']; ?>">
                                    <?= htmlspecialchars($item['nome']) . " - Estoque: " . $item['estoque_atual']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="tipo" class="form-label">Tipo de Movimentação</label>
                        <select class="form-control" id="tipo" name="tipo" required onchange="toggleValidade()">
                            <option value="entrada">Entrada</option>
                            <option value="saida">Saída</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="quantidade" class="form-label">Quantidade</label>
                        <input type="number" class="form-control" id="quantidade" name="quantidade" required min="1">
                    </div>

                    <div class="mb-3" id="validadeContainer">
                        <label for="validade" class="form-label">Data de Validade (Opcional)</label>
                        <input type="date" class="form-control" id="validade" name="validade">
                    </div>

                    <div class="mb-3">
                        <label for="observacao" class="form-label">Observação (Opcional)</label>
                        <textarea class="form-control" id="observacao" name="observacao" rows="3"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-save"></i> Registrar Movimentação</button>
                </form>
            </div>

            <div class="btn-group-custom mt-3">
                <a href="estoque.php" class="btn btn-secondary w-100"><i class="bi bi-arrow-left"></i> Voltar</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleValidade() {
            let tipo = document.getElementById("tipo").value;
            let validadeContainer = document.getElementById("validadeContainer");
            validadeContainer.style.display = tipo === "entrada" ? "block" : "none";
        }

        document.addEventListener("DOMContentLoaded", function () {
            toggleValidade();

            let toastSucesso = document.getElementById('toastSucesso');
            let toastErro = document.getElementById('toastErro');

            if (toastSucesso && <?= $sucesso ? 'true' : 'false' ?>) {
                toastSucesso.style.display = 'block';
                let toast = new bootstrap.Toast(toastSucesso);
                toast.show();
                setTimeout(() => toastSucesso.remove(), 5000);
            }

            if (toastErro && <?= $erro ? 'true' : 'false' ?>) {
                toastErro.style.display = 'block';
                let toast = new bootstrap.Toast(toastErro);
                toast.show();
                setTimeout(() => toastErro.remove(), 5000);
            }
        });
    </script>
</body>
</html>

<?php
session_start();
require_once "../dao/CategoriaDAO.php";

if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}

$categoriaDAO = new CategoriaDAO();
$categorias = $categoriaDAO->listarTodos();
$sucesso = isset($_GET['sucesso']);
$erro = isset($_GET['erro']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Categoria - AlmoxIF</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- CSS Personalizado -->
    <link rel="stylesheet" href="../public/styles.css">
</head>
<body>
    <?php include "sidebar.php"; ?> 
    <?php include "navbar.php"; ?>

    <!-- Conteúdo Principal -->
    <div class="main-content">
        <h4 class="fw-bold mb-4">Cadastro de Categoria</h4>

        <!-- Toasts -->
        <div class="toast-container position-fixed top-0 end-0 p-3">
            <?php if ($sucesso): ?>
                <div id="sucessoToast" class="toast align-items-center text-bg-success border-0 show" role="alert">
                    <div class="d-flex">
                        <div class="toast-body">
                            ✅ Categoria cadastrada com sucesso!
                        </div>
                        <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($erro): ?>
                <div id="erroToast" class="toast align-items-center text-bg-danger border-0 show" role="alert">
                    <div class="d-flex">
                        <div class="toast-body">
                            ❌ Erro ao cadastrar a categoria. Tente novamente.
                        </div>
                        <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Formulário de Cadastro -->
        <div class="card p-4">
            <form action="../dao/processa_categoria.php" method="POST">
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome da Categoria</label>
                    <input type="text" class="form-control" id="nome" name="nome" required>
                </div>
                <button type="submit" class="btn btn-primary-custom"><i class="bi bi-check-lg"></i> Cadastrar</button>
            </form>
        </div>

        <!-- Lista de Categorias -->
        <div class="mt-5">
            <h3 class="text-center text-secondary"><i class="bi bi-list-ul"></i> Categorias Cadastradas</h3>
            <div class="table-responsive">
                <table class="table table-striped table-hover mt-3 text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categorias as $categoria): ?>
                            <tr>
                                <td class="fw-bold">#<?= htmlspecialchars($categoria['id_categoria']); ?></td>
                                <td><?= htmlspecialchars($categoria['nome']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="dashboard.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Voltar ao Dashboard</a>
        </div>
    </div>

    <!-- Bootstrap JS -->
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

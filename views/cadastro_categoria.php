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
        <div class="container d-flex flex-column align-items-center">
            <h3 class="fw-bold mb-4 text-success"><i class="bi bi-tags"></i> Gerenciar Categorias</h3>

            <!-- Toasts -->
            <div class="toast-container position-fixed top-0 end-0 p-3">
                <?php if ($sucesso): ?>
                    <div class="toast align-items-center text-bg-success border-0 show" role="alert">
                        <div class="d-flex">
                            <div class="toast-body">✅ Categoria cadastrada com sucesso!</div>
                            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($erro): ?>
                    <div class="toast align-items-center text-bg-danger border-0 show" role="alert">
                        <div class="d-flex">
                            <div class="toast-body">❌ Erro ao cadastrar a categoria. Tente novamente.</div>
                            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Card com Formulário -->
            <div class="card shadow p-4 mb-5" style="width: 100%; max-width: 600px;">
                <h5 class="text-success fw-bold mb-3"><i class="bi bi-plus-circle"></i> Cadastrar Nova Categoria</h5>
                <form action="../dao/processa_categoria.php" method="POST">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome da Categoria</label>
                        <input type="text" class="form-control" id="nome" name="nome" placeholder="Digite o nome" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100 fw-bold"><i class="bi bi-check-lg"></i> Cadastrar</button>
                </form>
            </div>

            <!-- Lista de Categorias -->
            <div class="card shadow p-4 w-100" style="max-width: 900px;">
                <h5 class="text-secondary fw-bold mb-3"><i class="bi bi-list-ul"></i> Categorias Cadastradas</h5>
                <div class="table-responsive">
                    <table class="table table-striped table-hover text-center">
                        <thead class="table-success">
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

            <a href="dashboard.php" class="btn btn-outline-success mt-4"><i class="bi bi-arrow-left"></i> Voltar ao Dashboard</a>
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

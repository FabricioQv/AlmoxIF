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
require_once "../dao/RoleDAO.php";

$roleDAO = new RoleDAO();
$roles = $roleDAO->listarTodos();

// Verifica mensagens na URL
$sucesso = isset($_GET['sucesso']);
$erro = isset($_GET['erro']);
$duplicado = isset($_GET['duplicado']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário - Estoque IFSul</title>
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
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-success"><i class="bi bi-person-plus-fill"></i> Cadastro de Novo Usuário</h2>
    </div>

    <!-- Toasts -->
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <?php if ($sucesso): ?>
            <div class="toast align-items-center text-bg-success border-0 show" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        ✅ Usuário cadastrado com sucesso!
                    </div>
                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($erro): ?>
            <div class="toast align-items-center text-bg-danger border-0 show" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        ❌ Erro ao cadastrar o usuário. Tente novamente.
                    </div>
                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($duplicado): ?>
            <div class="toast align-items-center text-bg-warning border-0 show" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        ⚠️ Este login já está em uso. Escolha outro.
                    </div>
                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Formulário de Cadastro com Estilo -->
    <div class="card shadow-lg p-4 rounded-4" style="max-width: 600px; margin: auto;">
        <form action="../dao/processa_cadastro.php" method="POST">
            <div class="mb-3">
                <label for="nome" class="form-label fw-semibold">Nome Completo</label>
                <input type="text" class="form-control form-control-lg" id="nome" name="nome" required>
            </div>
            <div class="mb-3">
                <label for="siape" class="form-label fw-semibold">SIAPE <span class="text-muted">(Opcional)</span></label>
                <input type="text" class="form-control" id="siape" name="siape">
            </div>
            <div class="mb-3">
                <label for="login" class="form-label fw-semibold">Login</label>
                <input type="text" class="form-control" id="login" name="login" required>
            </div>
            <div class="mb-3">
                <label for="senha" class="form-label fw-semibold">Senha</label>
                <input type="password" class="form-control" id="senha" name="senha" required>
            </div>
            <div class="mb-4">
                <label for="role" class="form-label fw-semibold">Função</label>
                <select class="form-select" id="role" name="role" required>
                    <option value="">Selecione uma função</option>
                    <?php foreach ($roles as $role): ?>
                        <option value="<?= htmlspecialchars($role['id_role']) ?>">
                            <?= htmlspecialchars($role['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-success w-100 fw-bold">
                <i class="bi bi-check-circle"></i> Cadastrar Usuário
            </button>
        </form>
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

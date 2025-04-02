<?php
session_start();
if (isset($_SESSION["usuario"])) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Estoque IFSul</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- CSS Personalizado -->
    <link rel="stylesheet" href="../public/styles.css">
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">

    <div class="card p-5 rounded-4 shadow-lg" style="max-width: 420px; width: 100%;">
        <div class="text-center mb-4">
            <i class="bi bi-box-seam fs-1 text-success"></i>
            <h3 class="fw-bold mt-2">AlmoxIF</h3>
            <p class="text-muted mb-0">Acesso ao sistema</p>
        </div>

        <?php if (isset($_GET["erro"])): ?>
            <div class="alert alert-danger text-center p-2 mb-3">
                ❌ Login ou senha inválidos.
            </div>
        <?php endif; ?>

        <form action="../dao/processa_login.php" method="POST">
            <div class="mb-3">
                <label for="login" class="form-label fw-semibold">Login</label>
                <input type="text" class="form-control" id="login" name="login" placeholder="Digite seu login" required>
            </div>
            <div class="mb-3">
                <label for="senha" class="form-label fw-semibold">Senha</label>
                <input type="password" class="form-control" id="senha" name="senha" placeholder="Digite sua senha" required>
            </div>
            <button type="submit" class="btn btn-success w-100 fw-bold">
                <i class="bi bi-box-arrow-in-right me-1"></i> Entrar
            </button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}

$usuario = $_SESSION["usuario"];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Estoque IFSul</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../public/styles.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h3 class="mb-4"><i class="bi bi-box-seam"></i> Estoque IFSul</h3>
        <ul class="nav flex-column">
            <li class="nav-item mb-2">
                <a class="nav-link active" href="dashboard.php"><i class="bi bi-house-door"></i> Dashboard</a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link" href="estoque.php"><i class="bi bi-boxes"></i> Produtos</a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link" href="relatorios.php"><i class="bi bi-clipboard-data"></i> Relatórios</a>
            </li>
        </ul>
    </div>

    <!-- Barra Superior -->
    <nav class="navbar navbar-light bg-light px-3">
        <div class="container-fluid d-flex justify-content-end">
            <!-- Dropdown do usuário -->
            <div class="dropdown">
                <a class="nav-link dropdown-toggle text-dark" href="#" role="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    👤 <?= htmlspecialchars($usuario["nome"]); ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="perfil.php"><i class="bi bi-person-circle"></i> Meu Perfil</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="../dao/logout.php"><i class="bi bi-box-arrow-right"></i> Sair</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Conteúdo Principal -->
    <div class="main-content">
        <h2>Bem-vindo, <?= htmlspecialchars($usuario["nome"]); ?>! 🎉</h2>
        <p>Use o menu lateral para acessar as funcionalidades do sistema.</p>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

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
    <title>Meu Perfil - Estoque IFSul</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/styles.css">
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h3 class="mb-4"><i class="bi bi-box-seam"></i> Estoque IFSul</h3>
        <ul class="nav flex-column">
            <li class="nav-item mb-2">
                <a class="nav-link" href="dashboard.php"><i class="bi bi-house-door"></i> Dashboard</a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link" href="produtos.php"><i class="bi bi-boxes"></i> Produtos</a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link" href="relatorios.php"><i class="bi bi-clipboard-data"></i> Relatórios</a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link active" href="perfil.php"><i class="bi bi-person-circle"></i> Meu Perfil</a>
            </li>
        </ul>
    </div>

    <!-- Barra Superior -->
    <nav class="navbar navbar-light">
        <div class="container-fluid d-flex justify-content-end">
            <div class="dropdown">
                <a class="nav-link dropdown-toggle text-dark fw-bold" href="#" role="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle"></i> <?= htmlspecialchars($usuario["nome"]); ?>
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
        <div class="profile-card">
            <i class="bi bi-person-circle profile-avatar"></i>
            <h4 class="fw-bold"><?= htmlspecialchars($usuario["nome"]); ?></h4>
            <p><strong>Login:</strong> <?= htmlspecialchars($usuario["login"]); ?></p>
            <?php if (!empty($usuario["siape"])): ?>
                <p><strong>SIAPE:</strong> <?= htmlspecialchars($usuario["siape"]); ?></p>
            <?php endif; ?>
            <p><strong>Função:</strong> <?= ($usuario["fk_Role_id_role"] == 1) ? "Administrador" : "Estoquista"; ?></p>
            
            <div class="btn-group-custom">
                <a href="editar_perfil.php" class="btn btn-primary-custom"><i class="bi bi-pencil-square"></i> Editar Perfil</a>
                <a href="dashboard.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Voltar</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

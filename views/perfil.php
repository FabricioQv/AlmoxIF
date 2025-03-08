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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../public/styles.css">
</head>
<body>

    <?php include "sidebar.php"; ?> 
    <?php include "navbar.php"; ?>

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

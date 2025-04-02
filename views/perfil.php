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

<div class="main-content">
    <div class="container d-flex flex-column align-items-center">
        <div class="card shadow-lg p-4 rounded-4" style="max-width: 500px; width: 100%;">
            <div class="text-center mb-4">
                <i class="bi bi-person-circle text-success" style="font-size: 4rem;"></i>
                <h4 class="fw-bold mt-2"><?= htmlspecialchars($usuario["nome"]); ?></h4>
                <p class="text-muted mb-1"><i class="bi bi-person-badge"></i> <?= ($usuario["fk_Role_id_role"] == 1) ? "Administrador" : "Estoquista"; ?></p>
            </div>

            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Login:</strong> <?= htmlspecialchars($usuario["login"]); ?></li>
                <?php if (!empty($usuario["siape"])): ?>
                    <li class="list-group-item"><strong>SIAPE:</strong> <?= htmlspecialchars($usuario["siape"]); ?></li>
                <?php endif; ?>
            </ul>

            <div class="d-flex flex-column gap-2 mt-4">
                <a href="editar_perfil.php" class="btn btn-success w-100"><i class="bi bi-pencil-square"></i> Editar Perfil</a>
                <a href="dashboard.php" class="btn btn-outline-secondary w-100"><i class="bi bi-arrow-left"></i> Voltar</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

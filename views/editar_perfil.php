<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['usuario']['fk_Role_id_role'] == 3) {
    header("Location: estoque.php");
    exit();
}

$usuario = $_SESSION["usuario"];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil - Estoque IFSul</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../public/styles.css">
</head>
<body>

<?php include "sidebar.php"; ?> 
<?php include "navbar.php"; ?>

<div class="toast-container position-fixed top-0 end-0 p-3">
    <?php if (isset($_GET['sucesso'])): ?>
        <div id="sucessoToast" class="toast align-items-center text-bg-success border-0 show" role="alert">
            <div class="d-flex">
                <div class="toast-body">✅ Perfil atualizado com sucesso!</div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['erro'])): ?>
        <div id="erroToast" class="toast align-items-center text-bg-danger border-0 show" role="alert">
            <div class="d-flex">
                <div class="toast-body">❌ Erro ao atualizar o perfil. Tente novamente.</div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    <?php endif; ?>
</div>

<div class="main-content">
    <div class="container d-flex flex-column align-items-center">
        <div class="card shadow p-4 rounded-4" style="max-width: 500px; width: 100%;">
            <h3 class="fw-bold mb-3 text-success text-center"><i class="bi bi-pencil-square"></i> Editar Perfil</h3>

            <form action="../dao/processa_editar_perfil.php" method="POST">
                <div class="mb-3">
                    <label for="siape" class="form-label">SIAPE (Opcional)</label>
                    <input type="text" class="form-control" id="siape" name="siape" value="<?= htmlspecialchars($usuario['siape'] ?? ''); ?>">
                </div>

                <div class="mb-3">
                    <label for="senha_atual" class="form-label">Senha Atual</label>
                    <input type="password" class="form-control" id="senha_atual" name="senha_atual" required>
                </div>

                <div class="mb-3">
                    <label for="nova_senha" class="form-label">Nova Senha</label>
                    <input type="password" class="form-control" id="nova_senha" name="nova_senha">
                </div>

                <div class="mb-3">
                    <label for="confirmar_senha" class="form-label">Confirmar Nova Senha</label>
                    <input type="password" class="form-control" id="confirmar_senha" name="confirmar_senha">
                </div>

                <button type="submit" class="btn btn-success w-100 fw-bold mt-2">
                    <i class="bi bi-save"></i> Salvar Alterações
                </button>
            </form>

            <a href="perfil.php" class="btn btn-outline-secondary w-100 mt-3"><i class="bi bi-arrow-left"></i> Cancelar</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const sucessoToast = document.getElementById("sucessoToast");
        const erroToast = document.getElementById("erroToast");

        if (sucessoToast) {
            const toast = new bootstrap.Toast(sucessoToast);
            toast.show();
            setTimeout(() => sucessoToast.remove(), 5000);
        }

        if (erroToast) {
            const toast = new bootstrap.Toast(erroToast);
            toast.show();
            setTimeout(() => erroToast.remove(), 5000);
        }
    });
</script>
</body>
</html>

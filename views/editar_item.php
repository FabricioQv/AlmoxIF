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

require_once "../dao/ItemDAO.php";

$itemDAO = new ItemDAO();
$item = null;

if (isset($_GET["id"])) {
    $item = $itemDAO->buscarItemPorId($_GET["id"]);
    if (!$item) {
        header("Location: estoque.php?erro=item_nao_encontrado");
        exit();
    }
} else {
    header("Location: estoque.php?erro=sem_id");
    exit();
}

$sucesso = isset($_GET['sucesso']);
$erro = isset($_GET['erro']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Item - AlmoxIF</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../public/styles.css">
</head>
<body>

<?php include "sidebar.php"; ?>
<?php include "navbar.php"; ?>

<div class="main-content">
    <div class="profile-card">
        <div class="card shadow-lg p-4 rounded-4" style="max-width: 600px; width: 100%;">
            <div class="card-header bg-light mb-4 rounded-3 d-flex align-items-center">
                <i class="bi bi-pencil-square fs-4 text-success me-2"></i>
                <h4 class="mb-0 text-success">Editar Item</h4>
            </div>

            <form action="../dao/processa_edicao_item.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_item" value="<?= $item['id_item'] ?>">

                <div class="mb-3">
                    <label for="nome" class="form-label fw-semibold">Nome do Item</label>
                    <input type="text" class="form-control" id="nome" name="nome" required value="<?= htmlspecialchars($item['nome']) ?>">
                </div>

                <div class="mb-3">
                    <label for="codigo" class="form-label fw-semibold">Código</label>
                    <input type="text" class="form-control" id="codigo" name="codigo" required value="<?= htmlspecialchars($item['codigo']) ?>">
                </div>

                <div class="mb-3">
                    <label for="estoqueCritico" class="form-label fw-semibold">Estoque Crítico</label>
                    <input type="number" class="form-control" id="estoqueCritico" name="estoqueCritico" value="<?= htmlspecialchars($item['estoqueCritico'] ?? '') ?>">
                </div>

                <div class="mb-3">
                    <label for="imagem" class="form-label fw-semibold">Imagem do Item (Opcional)</label>
                    <input type="file" class="form-control" id="imagem" name="imagem" accept=".jpg, .jpeg, .png, .gif, .webp">
                    <?php if (!empty($item["imagem"])): ?>
                        <div class="mt-2">
                            <span class="d-block text-muted small">Imagem Atual:</span>
                            <img src="../uploads/<?= htmlspecialchars($item['imagem']) ?>" alt="Imagem do item" class="img-thumbnail mt-1" style="max-width: 150px;">
                        </div>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn btn-success w-100 py-2 fw-bold">
                    <i class="bi bi-save"></i> Salvar Alterações
                </button>
            </form>

            <div class="btn-group-custom mt-3">
                <a href="estoque.php" class="btn btn-outline-success w-100"><i class="bi bi-arrow-left"></i> Cancelar</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

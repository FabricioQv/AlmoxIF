<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}

require_once "../dao/ItemDAO.php";

$itemDAO = new ItemDAO();
$item = null;

// Verifica se o ID do item foi passado
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

// Mensagem de sucesso ou erro
$sucesso = isset($_GET['sucesso']);
$erro = isset($_GET['erro']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Item - AlmoxIF</title>
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/styles.css">
</head>
<body>

    <?php include "sidebar.php"; ?>
    <?php include "navbar.php"; ?>

    <div class="main-content">
        <h2><i class="bi bi-pencil-square"></i> Editar Item</h2>

        <form action="../dao/processa_edicao_item.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_item" value="<?= $item['id_item'] ?>">

            <div class="mb-3">
                <label for="nome" class="form-label">Nome do Item</label>
                <input type="text" class="form-control" id="nome" name="nome" required value="<?= htmlspecialchars($item['nome']) ?>">
            </div>

            <div class="mb-3">
                <label for="codigo" class="form-label">Código</label>
                <input type="text" class="form-control" id="codigo" name="codigo" required value="<?= htmlspecialchars($item['codigo']) ?>">
            </div>

            <div class="mb-3">
                <label for="estoqueCritico" class="form-label">Estoque Crítico</label>
                <input type="number" class="form-control" id="estoqueCritico" name="estoqueCritico" value="<?= htmlspecialchars($item['estoqueCritico'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label for="imagem" class="form-label">Imagem do Item (Opcional)</label>
                <input type="file" class="form-control" id="imagem" name="imagem" accept=".jpg, .jpeg, .png, .gif, .webp">
                <?php if (!empty($item["imagem"])): ?>
                    <p>Imagem Atual: <img src="../uploads/<?= htmlspecialchars($item['imagem']) ?>" alt="Imagem do item" width="100"></p>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Salvar Alterações</button>
            <a href="estoque.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Cancelar</a>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

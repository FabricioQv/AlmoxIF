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

require_once "../dao/CategoriaDAO.php";
$categoriaDAO = new CategoriaDAO();
$categorias = $categoriaDAO->listarTodos();

$sucesso = isset($_GET['sucesso']);
$erro = isset($_GET['erro']);
$erroCodigo = isset($_GET['erro']) && $_GET['erro'] === "codigo";
$erroImagem = isset($_GET['erro']) && $_GET['erro'] === "imagem";
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Item - Estoque IFSul</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../public/styles.css">
</head>
<body>

<?php include "sidebar.php"; ?> 
<?php include "navbar.php"; ?>

<div class="toast-container position-fixed top-0 end-0 p-3">
    <?php if ($sucesso): ?>
        <div class="toast align-items-center text-bg-success border-0 show" role="alert">
            <div class="d-flex">
                <div class="toast-body">✅ Item cadastrado com sucesso!</div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($erro): ?>
        <div class="toast align-items-center text-bg-danger border-0 show" role="alert">
            <div class="d-flex">
                <div class="toast-body">❌ Erro ao cadastrar o item. Tente novamente.</div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($erroCodigo): ?>
        <div class="toast align-items-center text-bg-warning border-0 show" role="alert">
            <div class="d-flex">
                <div class="toast-body">⚠️ Código do item já está em uso. Escolha outro.</div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($erroImagem): ?>
        <div class="toast align-items-center text-bg-warning border-0 show" role="alert">
            <div class="d-flex">
                <div class="toast-body">⚠️ Formato de imagem inválido. Use JPG, JPEG, PNG, GIF ou WEBP.</div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    <?php endif; ?>
</div>

<div class="main-content">
    <div class="container d-flex flex-column align-items-center">
        <h3 class="fw-bold mb-4 text-success"><i class="bi bi-box"></i> Cadastrar Novo Item</h3>

        <div class="card shadow p-4 mb-5 w-100" style="max-width: 700px;">
            <form action="../dao/processa_cadastro_item.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome do Item</label>
                    <input type="text" class="form-control" id="nome" name="nome" required>
                </div>
                <div class="mb-3">
                    <label for="codigo" class="form-label">Código</label>
                    <input type="text" class="form-control" id="codigo" name="codigo" required>
                </div>
                <div class="mb-3">
                    <label for="categoria" class="form-label">Categoria</label>
                    <select class="form-select" id="categoria" name="categoria" required>
                        <option value="">Selecione</option>
                        <?php foreach ($categorias as $categoria): ?>
                            <option value="<?= htmlspecialchars($categoria['id_categoria']) ?>">
                                <?= htmlspecialchars($categoria['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="estoqueCritico" class="form-label">Estoque Crítico</label>
                    <input type="number" class="form-control" id="estoqueCritico" name="estoqueCritico" min="0">
                </div>
                <div class="mb-3">
                    <label for="quantidade" class="form-label">Quantidade Inicial</label>
                    <input type="number" class="form-control" id="quantidade" name="quantidade" required min="1">
                </div>
                <div class="mb-3">
                    <label for="tipo" class="form-label">Tipo de Movimentação</label>
                    <select class="form-select" id="tipo" name="tipo" required>
                        <option value="entrada">Entrada</option>
                        <option value="saida">Saída</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="unidade" class="form-label">Unidade</label>
                    <input type="text" class="form-control" id="unidade" name="unidade" required>
                </div>
                <div class="mb-3">
                    <label for="validade" class="form-label">Validade</label>
                    <input type="date" class="form-control" id="validade" name="validade">
                </div>
                <div class="mb-3">
                    <label for="imagem" class="form-label">Imagem</label>
                    <input type="file" class="form-control" id="imagem" name="imagem" accept=".jpg, .jpeg, .png, .gif, .webp">
                </div>
                <button type="submit" class="btn btn-success w-100 mt-3 fw-bold">
                    <i class="bi bi-save"></i> Cadastrar Item
                </button>
            </form>
        </div>

        <a href="estoque.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Voltar ao Estoque
        </a>
    </div>
</div>

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

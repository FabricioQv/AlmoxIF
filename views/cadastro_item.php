<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}

require_once "../dao/CategoriaDAO.php";
$categoriaDAO = new CategoriaDAO();
$categorias = $categoriaDAO->listarTodos();

// Verifica se há mensagens de sucesso ou erro
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

    <!-- Toasts -->
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <?php if ($sucesso): ?>
            <div id="sucessoToast" class="toast align-items-center text-bg-success border-0 show" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        ✅ Item cadastrado com sucesso!
                    </div>
                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($erro): ?>
            <div id="erroToast" class="toast align-items-center text-bg-danger border-0 show" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        ❌ Erro ao cadastrar o item. Tente novamente.
                    </div>
                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($erroCodigo): ?>
            <div id="erroCodigoToast" class="toast align-items-center text-bg-warning border-0 show" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        ⚠️ Código do item já está em uso. Escolha outro.
                    </div>
                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($erroImagem): ?>
            <div id="erroImagemToast" class="toast align-items-center text-bg-warning border-0 show" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        ⚠️ Formato de imagem inválido. Use JPG, JPEG, PNG, GIF ou WEBP.
                    </div>
                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Conteúdo Principal -->
    <div class="main-content">
        <div class="profile-card">
            <h2><i class="bi bi-box"></i> Novo Item</h2>

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
                    <select class="form-control" id="categoria" name="categoria" required>
                        <option value="">Selecione uma categoria</option>
                        <?php foreach ($categorias as $categoria): ?>
                            <option value="<?= htmlspecialchars($categoria['id_categoria']) ?>">
                                <?= htmlspecialchars($categoria['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="estoqueCritico" class="form-label">Estoque Crítico (Opcional)</label>
                    <input type="number" class="form-control" id="estoqueCritico" name="estoqueCritico" min="0">
                </div>

                <div class="mb-3">
                    <label for="quantidade" class="form-label">Quantidade Inicial</label>
                    <input type="number" class="form-control" id="quantidade" name="quantidade" required min="1">
                </div>
                <div class="mb-3">
                    <label for="unidade" class="form-label">Unidade</label>
                    <input type="text" class="form-control" id="unidade" name="unidade" required>
                </div>
                <div class="mb-3">
                    <label for="validade" class="form-label">Data de Validade (Opcional)</label>
                    <input type="date" class="form-control" id="validade" name="validade">
                </div>

                <div class="mb-3">
                    <label for="imagem" class="form-label">Imagem do Item (Opcional)</label>
                    <input type="file" class="form-control" id="imagem" name="imagem" accept=".jpg, .jpeg, .png, .gif, .webp">
                </div>

                <button type="submit" class="btn btn-primary-custom"><i class="bi bi-save"></i> Cadastrar Item</button>
            </form>

            <div class="btn-group-custom">
                <a href="estoque.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Cancelar</a>
            </div>
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

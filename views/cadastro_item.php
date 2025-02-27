<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}

require_once "../dao/CategoriaDAO.php";
$categoriaDAO = new CategoriaDAO();
$categorias = $categoriaDAO->listarTodos();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Item - Estoque IFSul</title>
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
                <a class="nav-link" href="estoque.php"><i class="bi bi-boxes"></i> Estoque</a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link active" href="cadastro_item.php"><i class="bi bi-plus-circle"></i> Cadastrar Item</a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link" href="relatorios.php"><i class="bi bi-clipboard-data"></i> Relatórios</a>
            </li>
        </ul>
    </div>

    <!-- Barra Superior -->
    <nav class="navbar navbar-light">
        <div class="container-fluid d-flex justify-content-between">
            <h2 class="fw-bold"><i class="bi bi-plus-circle"></i> Cadastrar Novo Item</h2>
            <div>
                <a href="estoque.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Voltar</a>
            </div>
        </div>
    </nav>

    <!-- Conteúdo Principal -->
    <div class="main-content">
        <div class="profile-card">
            <h2><i class="bi bi-box"></i> Novo Item</h2>

            <form action="../dao/processa_cadastro_item.php" method="POST">
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
                        <input type="number" class="form-control" id="estoqueCritico" name="estoqueCritico" min="0" placeholder="Informe o estoque crítico">
                    </div>

                    <div class="mb-3">
                        <label for="quantidade" class="form-label">Quantidade Inicial</label>
                        <input type="number" class="form-control" id="quantidade" name="quantidade" required min="1">
                    </div>

                    <div class="mb-3">
                        <label for="validade" class="form-label">Data de Validade (Opcional)</label>
                        <input type="date" class="form-control" id="validade" name="validade">
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
</body>
</html>

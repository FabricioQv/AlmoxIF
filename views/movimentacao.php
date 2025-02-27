<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}

require_once "../dao/ItemDAO.php";
$itemDAO = new ItemDAO();

$itens = $itemDAO->listarEstoque();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movimentação de Estoque - Estoque IFSul</title>
    
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
                <a class="nav-link" href="cadastro_item.php"><i class="bi bi-plus-circle"></i> Cadastrar Item</a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link active" href="movimentacao.php"><i class="bi bi-arrow-left-right"></i> Movimentação</a>
            </li>
        </ul>
    </div>

    <!-- Barra Superior -->
    <nav class="navbar navbar-light">
        <div class="container-fluid d-flex justify-content-between">
            <h2 class="fw-bold"><i class="bi bi-arrow-left-right"></i> Movimentação de Estoque</h2>
        </div>
    </nav>

    <!-- Conteúdo Principal -->
    <div class="main-content">
        <div class="profile-card">
            <h2><i class="bi bi-arrow-left-right"></i> Registrar Movimentação</h2>

            <!-- Formulário de Movimentação -->
            <form action="../dao/processa_movimentacao.php" method="POST">
                <div class="mb-3">
                    <label for="item" class="form-label">Selecione o Item</label>
                    <select class="form-control" id="item" name="item" required>
                        <option value="">Escolha um item</option>
                        <?php foreach ($itens as $item): ?>
                            <option value="<?= $item['id_item']; ?>">
                                <?= htmlspecialchars($item['nome']) . " - Estoque: " . $item['estoque_atual']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="tipo" class="form-label">Tipo de Movimentação</label>
                    <select class="form-control" id="tipo" name="tipo" required>
                        <option value="entrada">Entrada</option>
                        <option value="saida">Saída</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="quantidade" class="form-label">Quantidade</label>
                    <input type="number" class="form-control" id="quantidade" name="quantidade" required min="1">
                </div>

                <div class="mb-3">
                    <label for="validade" class="form-label">Data de Validade (Opcional)</label>
                    <input type="date" class="form-control" id="validade" name="validade">
                </div>

                <button type="submit" class="btn btn-primary-custom"><i class="bi bi-save"></i> Registrar Movimentação</button>
            </form>

            <div class="btn-group-custom">
                <a href="estoque.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Voltar</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

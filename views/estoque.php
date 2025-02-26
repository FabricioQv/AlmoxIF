<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}

require_once "../dao/ItemDAO.php";
$itemDAO = new ItemDAO();

$termoBusca = isset($_GET["busca"]) ? trim($_GET["busca"]) : "";
$itens = $itemDAO->listarItens($termoBusca);

// Estatísticas simuladas (você pode pegar esses dados do banco depois)
$totalItens = 1234;
$itensBaixoEstoque = 27;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estoque - Estoque IFSul</title>
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
                <a class="nav-link active" href="estoque.php"><i class="bi bi-boxes"></i> Estoque</a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link" href="cadastro_item.php"><i class="bi bi-plus-circle"></i> Cadastrar Item</a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link" href="relatorios.php"><i class="bi bi-clipboard-data"></i> Relatórios</a>
            </li>
        </ul>
    </div>

    <!-- Barra Superior -->
    <nav class="navbar navbar-light">
        <div class="container-fluid d-flex justify-content-between">
            <h2 class="fw-bold"><i class="bi bi-box"></i> Estoque</h2>
            <div>
                <a href="cadastro_item.php" class="btn btn-success"><i class="bi bi-plus-lg"></i> Novo Produto</a>
            </div>
        </div>
    </nav>

    <!-- Conteúdo Principal -->
<div class="main-content">
    <!-- Cards de Estatísticas -->
    <div class="row g-4 mb-4 w-100">
        <div class="col-md-6">
            <div class="card stat-card">
                <div class="card-body">
                    <h5 class="card-title text-primary"><i class="bi bi-box"></i> Total de Itens</h5>
                    <h2 class="card-text"><?= number_format($totalItens, 0, ',', '.'); ?></h2>
                    <span class="text-success"><i class="bi bi-arrow-up"></i> 12%</span> vs mês anterior
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card stat-card">
                <div class="card-body">
                    <h5 class="card-title text-warning"><i class="bi bi-exclamation-triangle"></i> Baixo Estoque</h5>
                    <h2 class="card-text"><?= number_format($itensBaixoEstoque, 0, ',', '.'); ?></h2>
                    <span class="text-danger"><i class="bi bi-arrow-down"></i> 5%</span> itens críticos
                </div>
            </div>
        </div>
    </div>

    <!-- Barra de Pesquisa -->
    <div class="d-flex justify-content-between align-items-center mb-3 w-100">
        <h4 class="fw-bold">📦 Lista de Itens no Estoque</h4>
        <form action="estoque.php" method="GET" class="d-flex">
            <input type="text" class="form-control me-2" name="busca" placeholder="Buscar por nome" value="<?= htmlspecialchars($termoBusca); ?>">
            <button type="submit" class="btn btn-primary-custom"><i class="bi bi-search"></i></button>
        </form>
    </div>

    <!-- Tabela de Itens -->
    <div class="table-responsive table-custom w-100">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Imagem</th>
                    <th>Nome</th>
                    <th>Código</th>
                    <th>Quantidade</th>
                    <th>Validade</th>
                    <th>Categoria</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($itens)): ?>
                    <tr>
                        <td colspan="6" class="text-center">Nenhum item encontrado.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($itens as $item): ?>
                        <tr>
                            <td>
                                <img src="../uploads/<?= !empty($item["imagem"]) ? htmlspecialchars($item["imagem"]) : 'default.png'; ?>" 
                                     alt="Imagem do item" 
                                     class="item-img">
                            </td>
                            <td><?= htmlspecialchars($item["nome"]); ?></td>
                            <td><?= htmlspecialchars($item["codigo"]); ?></td>
                            <td><?= htmlspecialchars($item["quantidadeEstoque"]); ?></td>
                            <td><?= $item["dataValidade"] ? date("d/m/Y", strtotime($item["dataValidade"])) : "Sem validade"; ?></td>
                            <td><?= htmlspecialchars($item["categoria_nome"]); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>



    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

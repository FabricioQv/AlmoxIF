<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}

require_once "../dao/ItemDAO.php";
$itemDAO = new ItemDAO();

$itens = $itemDAO->listarEstoque();
$usuario = $_SESSION["usuario"]; // Pegando os dados do usuário para exibir na navbar
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
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
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
                <a class="nav-link" href="movimentacao.php"><i class="bi bi-arrow-left-right"></i> Movimentação</a>
            </li>
        </ul>
    </div>

    <!-- Barra Superior (igual ao dashboard.php) -->
    <nav class="navbar navbar-light bg-light px-3">
        <div class="container-fluid d-flex justify-content-end">
            <!-- Dropdown do usuário -->
            <div class="dropdown">
                <a class="nav-link dropdown-toggle text-dark" href="#" role="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    👤 <?= htmlspecialchars($usuario["nome"]); ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="perfil.php"><i class="bi bi-person-circle"></i> Meu Perfil</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="../dao/logout.php"><i class="bi bi-box-arrow-right"></i> Sair</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Conteúdo Principal -->
    <div class="main-content">
        <div class="table-responsive table-custom w-100">
            <table id="tabelaEstoque" class="table table-striped table-bordered dataTable">
                <thead>
                    <tr>
                        <th>Imagem</th>
                        <th>Nome</th>
                        <th>Estoque Atual</th>
                        <th>Estoque Crítico</th>
                        <th>Validade</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($itens as $item): ?>
                        <tr>
                            <td>
                                <img src="../uploads/<?= !empty($item["imagem"]) ? htmlspecialchars($item["imagem"]) : 'default.png'; ?>" 
                                     alt="Imagem do item" 
                                     class="item-img"
                                     this.alt='Imagem não encontrada';">
                            </td>
                            <td><?= htmlspecialchars($item["nome"]); ?></td>
                            <td><?= htmlspecialchars($item["estoque_atual"]); ?></td>
                            <td><?= ($item["estoqueCritico"] !== null) ? htmlspecialchars($item["estoqueCritico"]) : '—'; ?></td>
                            <td>
                                <?php if (!empty($item["validade"])): ?>
                                    <span class="badge bg-success">
                                        <?= date("d/m/Y", strtotime($item["validade"])); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Não Perecível</span>
                                <?php endif; ?>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#tabelaEstoque').DataTable({
                pageLength: 10, // Itens por página
                lengthMenu: [10, 25, 50, 100], // Opções de quantidade de registros
                order: [[1, 'asc']], // Ordenar por nome
                responsive: true, // Tabela responsiva
                language: {
                    search: "Pesquisar:",
                    lengthMenu: "Mostrar _MENU_ registros por página",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    infoFiltered: "(filtrado de _MAX_ no total)",
                    paginate: {
                        first: "Primeiro",
                        last: "Último",
                        next: "Próximo",
                        previous: "Anterior"
                    }
                },
                ordering: true,
            });
        });
    </script>

</body>
</html>

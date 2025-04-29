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
require_once "../dao/MovimentoDAO.php";
$movimentoDAO = new MovimentoDAO();
$movimentacoes = $movimentoDAO->listarMovimentacoes();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Últimas Movimentações - Estoque IFSul</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/styles.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        /* Permitir quebra de linha na coluna de observação */
        .dt-nowrap {
            white-space: normal !important;
            word-wrap: break-word;
            max-width: 250px;
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <?php include 'navbar.php'; ?>

    <!-- Conteúdo Principal -->
    <div class="main-content">
        <h2 class="fw-bold mb-4"><i class="bi bi-clock-history"></i> Últimas Movimentações</h2>
        
        <div class="table-responsive table-custom w-100">
            <table id="tabelaMovimentacoes" class="table table-striped table-bordered dataTable">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Tipo</th>
                        <th>Quantidade</th>
                        <th>Validade</th>
                        <th>Data da Movimentação</th>
                        <th>Usuário</th>
                        <th>Observação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($movimentacoes as $mov): ?>
                        <tr>
                            <td><?= htmlspecialchars($mov["item_nome"]); ?></td>
                            <td>
                                <span class="badge <?= $mov["tipo"] === 'entrada' ? 'bg-success' : 'bg-danger'; ?>">
                                    <?= ucfirst($mov["tipo"]); ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($mov["quantidade"]); ?></td>
                            <td>
                                <?php 
                                    $validade = $mov["validade_mais_proxima"] ?? null;
                                    if ($validade) {
                                        echo "<span class='badge bg-primary'>" . date("d/m/Y", strtotime($validade)) . "</span>";
                                    } else {
                                        echo "<span class='badge bg-secondary'>Não Perecível</span>";
                                    }
                                ?>
                            </td>
                            <td><?= htmlspecialchars($mov["data_movimento"]); ?></td>
                            <td><?= htmlspecialchars($mov["usuario_nome"]); ?></td>
                            <td class="dt-nowrap" data-bs-toggle="tooltip" title="<?= htmlspecialchars($mov["observacao"]); ?>">
                                <?= !empty($mov["observacao"]) ? htmlspecialchars($mov["observacao"]) : '—'; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- jQuery e Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#tabelaMovimentacoes').DataTable({
                "columnDefs": [
                    { "className": "dt-nowrap", "targets": [6] } // Índice da coluna de observação
                ],
                "pageLength": 10,
                "lengthMenu": [10, 25, 50, 100],
                "order": [[4, 'desc']],
                "responsive": true,
                "language": {
                    "search": "Pesquisar:",
                    "lengthMenu": "Mostrar _MENU_ registros por página",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    "paginate": {
                        "first": "Primeiro",
                        "last": "Último",
                        "next": "Próximo",
                        "previous": "Anterior"
                    }
                }
            });

            // Ativar Tooltip do Bootstrap
            $('[data-bs-toggle="tooltip"]').tooltip();
        });
    </script>
</body>
</html>

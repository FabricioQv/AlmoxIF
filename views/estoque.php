<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}

require_once "../dao/MovimentoDAO.php";
$movimentoDAO = new MovimentoDAO();

$usuario = $_SESSION["usuario"];
$isProfessor = ($usuario['fk_Role_id_role'] == 3);

$itens = $isProfessor
    ? $movimentoDAO->listarEstoqueSimples()
    : $movimentoDAO->listarEstoque();

$itensBaixos = [];

if (!$isProfessor) {
    $itensBaixos = array_filter($itens, function ($item) {
        return !empty($item["estoque_baixo"]);
    });
}
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <?php include "sidebar.php"; ?> 
    <?php include "navbar.php"; ?>

    <div class="main-content">
        <div class="table-responsive table-custom w-100">
            <table id="tabelaEstoque" class="table table-striped table-bordered dataTable">
                <thead>
                    <tr>
                        <th>Imagem</th>
                        <th>Código</th>
                        <th>Nome</th>
                        <?php if (!$isProfessor): ?>
                            <th>Categoria</th>
                            <th>Unidade</th>
                        <?php endif; ?>
                        <th>Estoque Atual</th>
                        <?php if (!$isProfessor): ?>
                            <th>Estoque Crítico</th>
                            <th>Validade Mais Próxima</th>
                            <th>Editar</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($itens as $item): ?>
                        <tr class="<?= (!empty($item['estoque_baixo']) ? 'table-danger' : '') ?>">
                            <td>
                                <img src="../uploads/<?= !empty($item["imagem"]) ? htmlspecialchars($item["imagem"]) : 'default.png'; ?>" 
                                    alt="Imagem do item" 
                                    class="item-img"
                                    onerror="this.src='../uploads/default.png'; this.alt='Imagem não encontrada';">
                            </td>
                            <td><?= htmlspecialchars($item["codigo"]); ?></td>
                            <td><?= htmlspecialchars($item["nome"]); ?></td>
                            <?php if (!$isProfessor): ?>
                                <td><?= htmlspecialchars($item["categoria_nome"]); ?></td>
                                <td><?= htmlspecialchars($item["unidade"]); ?></td>
                            <?php endif; ?>
                            <td><?= htmlspecialchars($item["estoque_atual"] ?? 0); ?></td>
                            <?php if (!$isProfessor): ?>
                                <td><?= ($item["estoqueCritico"] !== null) ? htmlspecialchars($item["estoqueCritico"]) : '—'; ?></td>
                                <td>
                                    <?php 
                                        $validade = $item["validade_mais_proxima"] ?? null;
                                        if ($validade && $item["estoque_atual"] > 0) {
                                            $hoje = date("Y-m-d");
                                            $diferenca = (strtotime($validade) - strtotime($hoje)) / (60 * 60 * 24);
                                            $cor = ($diferenca <= 30) ? "bg-danger text-white" : "bg-success";
                                            echo "<span class='badge $cor'>" . date("d/m/Y", strtotime($validade)) . "</span>";
                                        } else {
                                            echo "<span class='badge bg-secondary'>Não Perecível</span>";
                                        }
                                    ?>
                                </td>
                                <td>
                                    <a href="editar_item.php?id=<?= $item['id_item']; ?>" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i> Editar
                                    </a>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="toast-container position-fixed top-0 end-0 p-3">
        <?php if (!empty($itensBaixos)): ?>
            <div id="estoqueBaixoToast" class="toast align-items-center text-bg-warning border-0 show" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        ⚠️ Atenção! Alguns itens estão abaixo do estoque crítico.
                    </div>
                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        <?php endif; ?>
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
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                order: [[1, 'asc']],
                responsive: true,
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

        document.addEventListener("DOMContentLoaded", function () {
            let toastEl = document.getElementById("estoqueBaixoToast");
            if (toastEl) {
                let toast = new bootstrap.Toast(toastEl);
                toast.show();
            }
        });
    </script>
</body>
</html>

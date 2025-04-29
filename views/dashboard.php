<?php
session_start();
require_once "../dao/MovimentoDAO.php";

if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['usuario']['fk_Role_id_role'] == 3) {
    header("Location: estoque.php");
    exit();
}

$movimentoDAO = new MovimentoDAO();

$itemIdSelecionado = $_GET['item_id'] ?? null;
$dadosMovimentacao = $movimentoDAO->obterMovimentacaoMensal($itemIdSelecionado);
$itensMaisMovimentados = $movimentoDAO->obterItensMaisMovimentados($itemIdSelecionado);

include 'sidebar.php';
include 'navbar.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Estoque IFSul</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="../public/styles.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="main-content">
    <h2 class="fw-bold mb-4"><i class="bi bi-bar-chart"></i> Dashboard</h2>

    <!-- Filtro por item -->
    <form method="GET" class="dashboard-filter-form d-flex justify-content-end align-items-center gap-2 mb-4">
        <label for="item_id" class="fw-semibold mb-0">Filtrar por Item:</label>
        <select name="item_id" id="item_id" class="form-select select2-item" style="width: 300px;">
            <option value="">Todos os Itens</option>
            <?php
            require_once "../dao/ItemDAO.php";
            $itemDAO = new ItemDAO();
            $itens = $itemDAO->listarEstoque();
            foreach ($itens as $item) {
                $selected = ($itemIdSelecionado == $item['id_item']) ? "selected" : "";
                echo "<option value='{$item['id_item']}' $selected>" . htmlspecialchars($item['nome']) . "</option>";
            }
            ?>
        </select>
        <button type="submit" class="btn btn-success"><i class="bi bi-funnel"></i> Aplicar</button>
    </form>

    <!-- Gráficos -->
    <div class="row">
        <!-- Gráfico de Barras -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <i class="bi bi-graph-up"></i> Movimentação Mensal
                </div>
                <div class="card-body">
                    <canvas id="movimentacaoChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Gráfico de Pizza -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <i class="bi bi-box-seam"></i> Itens Mais Movimentados
                </div>
                <div class="card-body">
                    <canvas id="itensMaisMovimentadosChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function () {
        $('#item_id').select2({
            placeholder: "Escolha um item",
            allowClear: true,
            width: 'resolve'
        });
    });

    document.addEventListener("DOMContentLoaded", function () {
        const movimentacaoData = <?= json_encode($dadosMovimentacao); ?>;
        const itensMaisMovData = <?= json_encode($itensMaisMovimentados); ?>;

        if (movimentacaoData.length > 0) {
            const meses = movimentacaoData.map(m => m.mes);
            const entradas = movimentacaoData.map(m => parseInt(m.total_entrada) || 0);
            const saidas = movimentacaoData.map(m => parseInt(m.total_saida) || 0);

            new Chart(document.getElementById("movimentacaoChart"), {
                type: 'bar',
                data: {
                    labels: meses,
                    datasets: [
                        {
                            label: 'Entradas',
                            backgroundColor: 'rgba(72, 201, 176, 0.7)',
                            borderColor: 'rgba(72, 201, 176, 1)',
                            borderWidth: 1,
                            data: entradas
                        },
                        {
                            label: 'Saídas',
                            backgroundColor: 'rgba(255, 99, 132, 0.7)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1,
                            data: saidas
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        }

        if (itensMaisMovData.length > 0) {
            const itemLabels = itensMaisMovData.map(item => item.item_nome);
            const itemQuantidades = itensMaisMovData.map(item => parseInt(item.total_movimentado) || 0);

            new Chart(document.getElementById("itensMaisMovimentadosChart"), {
                type: 'pie',
                data: {
                    labels: itemLabels,
                    datasets: [{
                        data: itemQuantidades,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.7)',
                            'rgba(54, 162, 235, 0.7)',
                            'rgba(255, 206, 86, 0.7)',
                            'rgba(75, 192, 192, 0.7)',
                            'rgba(153, 102, 255, 0.7)'
                        ]
                    }]
                }
            });
        }
    });
</script>
</body>
</html>

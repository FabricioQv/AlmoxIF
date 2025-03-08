<?php
session_start();
require_once "../dao/MovimentoDAO.php";

if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}

$movimentoDAO = new MovimentoDAO();
$dadosMovimentacao = $movimentoDAO->obterMovimentacaoMensal();
$itensMaisMovimentados = $movimentoDAO->obterItensMaisMovimentados();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Estoque IFSul</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../public/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <?php include 'navbar.php'; ?>

    <div class="main-content">
        <h2 class="fw-bold mb-4"><i class="bi bi-bar-chart"></i> Dashboard</h2>

        <div class="row">
            <!-- Gráfico de Barras: Entradas e Saídas Mensais -->
            <div class="col-md-8 mb-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <i class="bi bi-graph-up"></i> Movimentação Mensal
                    </div>
                    <div class="card-body">
                        <canvas id="movimentacaoChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Gráfico de Pizza: Itens Mais Movimentados -->
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

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let movimentacaoData = <?= json_encode($dadosMovimentacao); ?>;
            let itensMaisMovData = <?= json_encode($itensMaisMovimentados); ?>;

            // Garantindo que há dados antes de tentar criar os gráficos
            if (movimentacaoData.length > 0) {
                let meses = movimentacaoData.map(m => m.mes);
                let entradas = movimentacaoData.map(m => parseInt(m.total_entrada) || 0);
                let saidas = movimentacaoData.map(m => parseInt(m.total_saida) || 0);

                let ctx1 = document.getElementById("movimentacaoChart").getContext("2d");
                new Chart(ctx1, {
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
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            if (itensMaisMovData.length > 0) {
                let itemLabels = itensMaisMovData.map(item => item.item_nome);
                let itemQuantidades = itensMaisMovData.map(item => parseInt(item.total_movimentado) || 0);

                let ctx2 = document.getElementById("itensMaisMovimentadosChart").getContext("2d");
                new Chart(ctx2, {
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

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
    $estoqueCritico = !empty($item["estoque_baixo"]);

    $validade = $item["validade_mais_proxima"] ?? null;
    $dataAtual = date("Y-m-d");
    $diasRestantes = $validade ? (strtotime($validade) - strtotime($dataAtual)) / (60 * 60 * 24) : null;
    $validadeProxima = $validade && $diasRestantes <= 30;

    return $estoqueCritico || $validadeProxima;
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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
<?php include "sidebar.php"; ?>
<?php include "navbar.php"; ?>

<!-- Toast de alerta -->
<div class="toast-container position-fixed top-0 end-0 p-3">
    <?php if (!empty($itensBaixos)): ?>
        <div class="toast align-items-center text-bg-warning border-0 show" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    ⚠️ <strong>Atenção:</strong> Os seguintes itens estão com <strong>estoque crítico</strong> ou <strong>validade próxima</strong>:
                    <ul class="mb-0 mt-2 ps-3">
                        <?php foreach ($itensBaixos as $item): ?>
<<<<<<< Updated upstream
                            <li><?= htmlspecialchars($item["nome"]); ?> (<?= htmlspecialchars($item["codigo"]); ?>)</li>
                        <?php endforeach; ?>
                    </ul>
=======
                            <?php
                                $mensagem = htmlspecialchars($item["nome"]) . " (" . htmlspecialchars($item["codigo"]) . ")";
                                $avisos = [];

                                if (!empty($item["estoque_baixo"])) {
                                    $avisos[] = "estoque crítico";
                                }

                                if (!empty($item["validade_mais_proxima"])) {
                                    $dias = (int)((strtotime($item["validade_mais_proxima"]) - strtotime(date("Y-m-d"))) / 86400);
                                    if ($dias < 0) {
                                        $avisos[] = "vencido há " . abs($dias) . " dias";
                                    } elseif ($dias === 0) {
                                        $avisos[] = "vence hoje";
                                    } else {
                                        $avisos[] = "vence em $dias dias";
                                    }
                                }

                                if (!empty($avisos)) {
                                    $mensagem .= " — " . implode(" e ", $avisos);
                                }
                            ?>
                            <li><?= $mensagem ?></li>
                        <?php endforeach; ?>
                    </ul>
>
>>>>>>> Stashed changes
                </div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    <?php endif; ?>
</div>

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
<<<<<<< Updated upstream
                            alt="Imagem do item"
                            class="item-img"
                            style="cursor:pointer"
                            data-bs-toggle="modal"
                            data-bs-target="#imagemModal"
                            data-img="../uploads/<?= !empty($item["imagem"]) ? htmlspecialchars($item["imagem"]) : 'default.png'; ?>"
                            onerror="this.src='../uploads/default.png'; this.alt='Imagem não encontrada';">

=======
                             alt="Imagem do item"
                             class="item-img"
                             onerror="this.src='../uploads/default.png'; this.alt='Imagem não encontrada';">
>>>>>>> Stashed changes
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
                            <a href="editar_item.php?id=<?= $item['id_item']; ?>" class="btn btn-sm buttonEdit">
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

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<<<<<<< Updated upstream
<!-- Modal de Imagem -->
<div class="modal fade" id="imagemModal" tabindex="-1" aria-labelledby="imagemModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="imagemModalLabel">Visualizar Imagem</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body text-center">
        <img id="imagemExpandida" src="" class="img-fluid" alt="Imagem ampliada">
      </div>
    </div>
  </div>
</div>


=======

>>>>>>> Stashed changes
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let toastList = document.querySelectorAll(".toast");
        toastList.forEach(toastEl => {
            let toast = new bootstrap.Toast(toastEl);
            toast.show();
            setTimeout(() => toastEl.remove(), 5000);
        });

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
</script>
<<<<<<< Updated upstream
<script>
document.addEventListener('DOMContentLoaded', function () {
    var imagemModal = document.getElementById('imagemModal');
    imagemModal.addEventListener('show.bs.modal', function (event) {
        var trigger = event.relatedTarget;
        var src = trigger.getAttribute('data-img');
        var img = document.getElementById('imagemExpandida');
        img.src = src;
    });
});
</script>
=======
>>>>>>> Stashed changes
</body>
</html>

<?php
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}

require_once "../dao/ItemDAO.php";
require_once "../dao/MovimentoDAO.php";
require_once "../services/leitor_pdf_service.php";

$itemDAO = new ItemDAO();
$movimentoDAO = new MovimentoDAO();

$itens = $itemDAO->listarEstoque();
$sucesso = isset($_GET['sucesso']);
$erro = isset($_GET['erro']);
$mensagemSucesso = null;
$mensagemErro = null;

// Processamento das movimentações vindas do modal AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['movimentacoes'])) {
    try {
        foreach ($_POST['movimentacoes'] as $movimentacao) {
            $codigo = $movimentacao['codigo'];
            $quantidade = (int)$movimentacao['quantidade'];
            $tipo = $movimentacao['tipo'];

            $item = $itemDAO->buscarPorCodigo($codigo);

            if ($item) {
                $movimento = new Movimento(
                    $item['id_item'],
                    $_SESSION['usuario']['id_usuario'],
                    $tipo,
                    $quantidade,
                    null,
                    "Movimentação via importação de PDF"
                );
                $movimentoDAO->registrarMovimento($movimento);
            }
        }
        $mensagemSucesso = "Movimentações registradas com sucesso!";
    } catch (Exception $e) {
        $mensagemErro = "Erro ao registrar movimentações: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Movimentação de Estoque - Estoque IFSul</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css"/>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="../public/styles.css"/>
</head>
<body>

<?php include "sidebar.php"; ?>
<?php include "navbar.php"; ?>

<!-- Toast de Mensagens -->
<div class="toast-container position-fixed top-0 end-0 p-3">
    <?php if ($mensagemSucesso): ?>
        <div class="toast align-items-center text-bg-success border-0 show" role="alert">
            <div class="d-flex">
                <div class="toast-body"><?= htmlspecialchars($mensagemSucesso) ?></div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    <?php elseif ($mensagemErro): ?>
        <div class="toast align-items-center text-bg-danger border-0 show" role="alert">
            <div class="d-flex">
                <div class="toast-body"><?= htmlspecialchars($mensagemErro) ?></div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Conteúdo Principal -->
<div class="main-content">

    <!-- Formulário Manual -->
    <div class="profile-card mb-5">
        <div class="card p-4 shadow-lg rounded-4" style="width: 500px;">
            <div class="card-header bg-light rounded-3 mb-4 d-flex align-items-center">
                <i class="bi bi-arrow-left-right fs-4 text-success me-2"></i>
                <h4 class="mb-0 text-success">Registrar Movimentação Manual</h4>
            </div>

            <form action="../dao/processa_movimentacao.php" method="POST">
                <div class="mb-4">
                    <label for="item" class="form-label fw-semibold">Selecione o Item</label>
                    <select class="form-control item-select w-100" id="item" name="item" required>
                        <option value="">Escolha um item</option>
                        <?php foreach ($itens as $item): ?>
                            <option value="<?= $item['id_item']; ?>" 
                                title="<?= htmlspecialchars($item['nome']) . " - Estoque: " . $item['estoque_atual']; ?>">
                                <?= mb_strimwidth(htmlspecialchars($item['nome']), 0, 40, "...") ?> - (Estoque: <?= $item['estoque_atual']; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="tipo" class="form-label fw-semibold">Tipo de Movimentação</label>
                    <select class="form-control" id="tipo" name="tipo" required onchange="toggleValidade()">
                        <option value="entrada">Entrada</option>
                        <option value="saida">Saída</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="quantidade" class="form-label fw-semibold">Quantidade</label>
                    <input type="number" class="form-control" id="quantidade" name="quantidade" required min="1">
                </div>

                <div class="mb-4" id="validadeContainer">
                    <label for="validade" class="form-label fw-semibold">Data de Validade (Opcional)</label>
                    <input type="date" class="form-control" id="validade" name="validade">
                </div>

                <div class="mb-4">
                    <label for="observacao" class="form-label fw-semibold">Observação (Opcional)</label>
                    <textarea class="form-control" id="observacao" name="observacao" rows="3"></textarea>
                </div>

                <button type="submit" class="btn btn-success w-100 py-2 fw-bold">
                    <i class="bi bi-save"></i> Registrar Movimentação
                </button>
            </form>

            <div class="btn-group-custom mt-3">
                <a href="estoque.php" class="btn btn-outline-success w-100">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>
            </div>
        </div>
    </div>

    <!-- Importação via PDF com Modal -->
    <div class="card p-4 shadow-lg rounded-4">
        <div class="card-header bg-light rounded-3 mb-4 d-flex align-items-center">
            <i class="bi bi-file-earmark-arrow-up fs-4 text-success me-2"></i>
            <h4 class="mb-0 text-success">Importar Movimentação via PDF</h4>
        </div>

        <form id="formImportarPDF" enctype="multipart/form-data" class="mb-4">
            <div class="mb-3">
                <label for="pdf" class="form-label">Selecione o arquivo PDF:</label>
                <input type="file" name="pdf" id="pdf" class="form-control" accept="application/pdf" required>
            </div>
            <button type="submit" class="btn btn-success fw-bold">Processar PDF</button>
        </form>
    </div>
</div>

<!-- Modal de Itens Encontrados -->
<div class="modal fade" id="modalItensEncontrados" tabindex="-1" aria-labelledby="modalItensEncontradosLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="modalItensEncontradosLabel">Itens Encontrados no PDF</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <form method="POST" id="formConfirmarMovimentacoes">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Código (PDF)</th>
                <th>Código (Banco)</th>
                <th>Descrição</th>
                <th>Quantidade</th>
                <th>Tipo de Movimentação</th>
              </tr>
            </thead>
            <tbody id="tabelaItensModal"></tbody>
          </table>
          <button type="submit" class="btn btn-success fw-bold">Confirmar Movimentações</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

<script>
function toggleValidade() {
    let tipo = document.getElementById("tipo").value;
    let validadeContainer = document.getElementById("validadeContainer");
    validadeContainer.style.display = tipo === "entrada" ? "block" : "none";
}

document.addEventListener("DOMContentLoaded", function () {
    toggleValidade();

    $('#item').select2({
        placeholder: "Escolha um item",
        allowClear: true,
        width: '100%'
    });

    document.getElementById('formImportarPDF').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch('../dao/processar_pdf_ajax.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            if (result.success && result.data.length > 0) {
                const tbody = document.getElementById('tabelaItensModal');
                tbody.innerHTML = '';

                result.data.forEach((item, index) => {
                    const row = `
                        <tr>
                            <td>${item.codigo_pdf}</td>
                            <td>${item.codigo}</td>
                            <td>${item.nome}</td>
                            <td>${item.quantidade}</td>
                            <td>
                                <select name="movimentacoes[${index}][tipo]" class="form-select" required>
                                    <option value="entrada">Entrada</option>
                                    <option value="saida">Saída</option>
                                </select>
                                <input type="hidden" name="movimentacoes[${index}][codigo]" value="${item.codigo}">
                                <input type="hidden" name="movimentacoes[${index}][quantidade]" value="${item.quantidade}">
                            </td>
                        </tr>
                    `;
                    tbody.insertAdjacentHTML('beforeend', row);
                });

                const modal = new bootstrap.Modal(document.getElementById('modalItensEncontrados'));
                modal.show();
            } else {
                alert('Nenhum item encontrado no PDF.');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao processar o PDF.');
        });
    });
});
</script>

</body>
</html>

<?php
session_start();
require_once "../dao/CategoriaDAO.php";

if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['usuario']['fk_Role_id_role'] == 3) {
    header("Location: estoque.php");
    exit();
}

$categoriaDAO = new CategoriaDAO();
$categorias = $categoriaDAO->listarTodos();
$sucesso = isset($_GET['sucesso']);
$erro = isset($_GET['erro']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Categoria - AlmoxIF</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../public/styles.css">
</head>
<body>
<?php include "sidebar.php"; ?> 
<?php include "navbar.php"; ?>

<div class="main-content">
    <div class="container d-flex flex-column align-items-center">
        <h3 class="fw-bold mb-4 text-success"><i class="bi bi-tags"></i> Gerenciar Categorias</h3>

        <div class="toast-container position-fixed top-0 end-0 p-3"></div>

        <div class="card shadow p-4 mb-5" style="width: 100%; max-width: 600px;">
            <h5 class="text-success fw-bold mb-3"><i class="bi bi-plus-circle"></i> Cadastrar Nova Categoria</h5>
            <form action="../dao/processa_categoria.php" method="POST">
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome da Categoria</label>
                    <input type="text" class="form-control" id="nome" name="nome" placeholder="Digite o nome" required>
                </div>
                <button type="submit" class="btn btn-success w-100 fw-bold"><i class="bi bi-check-lg"></i> Cadastrar</button>
            </form>
        </div>

        <div class="card shadow p-4 w-100" style="max-width: 900px;">
            <h5 class="text-secondary fw-bold mb-3"><i class="bi bi-list-ul"></i> Categorias Cadastradas</h5>
            <div class="table-responsive">
                <table class="table table-striped table-hover text-center">
                    <thead class="table-success">
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categorias as $categoria): ?>
                            <tr>
                                <td class="fw-bold">#<?= htmlspecialchars($categoria['id_categoria']); ?></td>
                                <td><?= htmlspecialchars($categoria['nome']) ?></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary editar-categoria"
                                            data-id="<?= $categoria['id_categoria'] ?>"
                                            data-nome="<?= htmlspecialchars($categoria['nome']) ?>">
                                        <i class="bi bi-pencil"></i> Editar
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <a href="dashboard.php" class="btn btn-outline-success mt-4"><i class="bi bi-arrow-left"></i> Voltar ao Dashboard</a>
    </div>
</div>

<!-- Modal de edição -->
<div class="modal fade" id="modalEditarCategoria" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow">
      <div class="modal-header">
        <h5 class="modal-title">Editar Categoria</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="editarCategoriaId">
        <div class="mb-3">
          <label for="editarCategoriaNome" class="form-label">Nome da Categoria</label>
          <input type="text" class="form-control" id="editarCategoriaNome">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success" id="salvarCategoriaBtn">Salvar</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
  const modal = new bootstrap.Modal(document.getElementById('modalEditarCategoria'));
  const idInput = document.getElementById('editarCategoriaId');
  const nomeInput = document.getElementById('editarCategoriaNome');

  document.querySelectorAll(".editar-categoria").forEach(botao => {
    botao.addEventListener("click", () => {
      idInput.value = botao.dataset.id;
      nomeInput.value = botao.dataset.nome;
      modal.show();
    });
  });

  document.getElementById("salvarCategoriaBtn").addEventListener("click", () => {
    const id = idInput.value;
    const nome = nomeInput.value.trim();
    if (nome === "") return alert("Nome não pode estar vazio.");

    fetch("../dao/atualiza_categoria.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `id=${id}&nome=${encodeURIComponent(nome)}`
    })
    .then(resp => resp.ok ? resp.text() : Promise.reject("Erro"))
    .then(() => {
      modal.hide();
      showToast("✅ Categoria atualizada com sucesso!", "success");
      setTimeout(() => location.reload(), 800);
    })
    .catch(() => {
      modal.hide();
      showToast("❌ Erro ao atualizar categoria.", "danger");
    });
  });

  function showToast(mensagem, tipo) {
    const container = document.querySelector(".toast-container");
    const toast = document.createElement("div");
    toast.className = `toast align-items-center text-bg-${tipo} border-0 show`;
    toast.setAttribute("role", "alert");
    toast.innerHTML = `
      <div class="d-flex">
        <div class="toast-body">${mensagem}</div>
        <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>`;
    container.appendChild(toast);
    const bootstrapToast = new bootstrap.Toast(toast);
    bootstrapToast.show();
    setTimeout(() => toast.remove(), 5000);
  }
});
</script>
</body>
</html>

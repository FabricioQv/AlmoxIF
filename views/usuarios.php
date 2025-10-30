<?php
session_start();
if (!isset($_SESSION["usuario"]) || $_SESSION["usuario"]["fk_Role_id_role"] != 1) {
    header("Location: login.php");
    exit();
}
require_once "../dao/UsuarioDAO.php";
$dao = new UsuarioDAO();
$usuarios = $dao->listarTodos();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Usuários Cadastrados - AlmoxIF</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../public/styles.css">
</head>
<body>
<?php include "sidebar.php"; ?>
<?php include "navbar.php"; ?>

<div class="main-content container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-success mt-4"><i class="bi bi-people-fill"></i> Usuários Cadastrados</h2>
    </div>

    <table class="table table-hover table-bordered shadow-sm">
        <thead class="table-success">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>SIAPE</th>
                <th>Login</th>
                <th>Função</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($usuarios as $usuario): ?>
            <tr>
                <td><?= $usuario["id_usuario"] ?></td>
                <td><?= htmlspecialchars($usuario["nome"] ?? '') ?></td>
                <td><?= htmlspecialchars($usuario["siape"] ?? '') ?></td>
                <td><?= htmlspecialchars($usuario["login"] ?? '') ?></td>
                <td>
                    <?php
                        if ($usuario["fk_Role_id_role"] == 1) {
                            echo "Administrador";
                        } elseif ($usuario["fk_Role_id_role"] == 2) {
                            echo "Estoquista";
                        } elseif ($usuario["fk_Role_id_role"] == 3) {
                            echo "Servidor";
                        } else {
                            echo "Desconhecido";
                        }
                    ?>
                </td>
                <td>
                    <?php if ($_SESSION["usuario"]["id_usuario"] != $usuario["id_usuario"]): ?>
                        <button type="button" class="btn btn-sm btn-danger" onclick="abrirModalExclusao(<?= $usuario['id_usuario'] ?>)">
                            <i class="bi bi-trash"></i> Excluir
                        </button>
                    <?php else: ?>
                        <span class="text-muted">Você</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
    <!-- Modal de Confirmação -->
<div class="modal fade" id="modalConfirmacao" tabindex="-1" aria-labelledby="modalConfirmacaoLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-danger">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="modalConfirmacaoLabel"><i class="bi bi-exclamation-triangle-fill me-2"></i>Confirmar Exclusão</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        Tem certeza que deseja excluir este usuário? Esta ação não pode ser desfeita.
      </div>
      <div class="modal-footer">
        <form id="formExcluir" method="POST" action="../dao/excluir_usuario.php">
          <input type="hidden" name="id_usuario" id="usuarioIdConfirmar">
          <button type="submit" class="btn btn-danger fw-bold">Sim, excluir</button>
        </form>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<script>
function abrirModalExclusao(id) {
    document.getElementById('usuarioIdConfirmar').value = id;
    const modal = new bootstrap.Modal(document.getElementById('modalConfirmacao'));
    modal.show();
}
</script>


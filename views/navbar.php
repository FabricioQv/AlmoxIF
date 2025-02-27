<?php
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="dashboard.php">
            <i class="bi bi-box-seam"></i> Estoque IFSul
        </a>

        <!-- Botão para menu mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Itens do menu -->
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php"><i class="bi bi-house-door"></i> Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="estoque.php"><i class="bi bi-boxes"></i> Estoque</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="cadastro_item.php"><i class="bi bi-plus-circle"></i> Cadastrar Item</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="movimentacao.php"><i class="bi bi-arrow-left-right"></i> Movimentação</a>
                </li>

                <!-- Dropdown do Usuário -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION["usuario"]["nome"]); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="perfil.php"><i class="bi bi-person"></i> Perfil</a></li>
                        <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right"></i> Sair</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

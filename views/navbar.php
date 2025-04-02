<!-- Barra Superior -->
<nav class="navbar navbar-light bg-light px-3 border-bottom">
    <div class="container-fluid d-flex justify-content-end align-items-center gap-3">
        <!-- Dropdown do Usuário -->
        <div class="dropdown">
            <a class="nav-link dropdown-toggle text-dark fw-semibold" href="#" role="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                👤 <?= htmlspecialchars($_SESSION["usuario"]["nome"]); ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                <li><a class="dropdown-item" href="perfil.php"><i class="bi bi-person-circle me-1"></i> Meu Perfil</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="../dao/logout.php"><i class="bi bi-box-arrow-right me-1"></i> Sair</a></li>
            </ul>
        </div>
    </div>
</nav>


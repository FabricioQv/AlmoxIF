<!-- Barra Superior -->
<nav class="navbar navbar-light bg-light px-3">
    <div class="container-fluid d-flex justify-content-end">
        <!-- Dropdown do usuário -->
        <div class="dropdown">
            <a class="nav-link dropdown-toggle text-dark" href="#" role="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                👤 <?= htmlspecialchars($_SESSION["usuario"]["nome"]); ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                <li><a class="dropdown-item" href="perfil.php"><i class="bi bi-person-circle"></i> Meu Perfil</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="../dao/logout.php"><i class="bi bi-box-arrow-right"></i> Sair</a></li>
            </ul>
        </div>
    </div>
</nav>

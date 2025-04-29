<!-- Sidebar -->
<div class="sidebar enhanced-sidebar">
    <div class="text-center mb-4">
        <img src="../public/ifsul.png" alt="Logo IFSul" class="logo-sidebar mb-2">
        <h4 class="fw-bold text-white"><i class="bi bi-box-seam"></i> AlmoxIF</h4>
    </div>

    <ul class="nav flex-column">
        <!-- Dashboard --> 
        <?php if ($_SESSION["usuario"]["fk_Role_id_role"] =! 3): ?> 
            <li class="nav-item">
                <a class="nav-link nav-anim d-flex align-items-center sidebar-btn" href="dashboard.php">
                    <i class="bi bi-house-door me-2 icon-anim"></i> <span>Dashboard</span>
                </a>
            </li>
        <?php endif; ?>
        <!-- Estoque (Expansível) -->
        <li class="nav-item">
            <a class="nav-link toggle-menu nav-anim d-flex align-items-center sidebar-btn" href="#">
                <i class="bi bi-boxes me-2 icon-anim"></i> <span>Estoque</span>
                <i class="bi bi-chevron-down ms-auto chevron-icon"></i>
            </a>
            <ul class="nav flex-column sub-menu ms-3 mt-1">
                <li><a class="nav-link nav-anim d-flex align-items-center sidebar-btn" href="estoque.php"><i class="bi bi-eye me-2"></i> Visualizar Estoque</a></li>
                <?php if ($_SESSION["usuario"]["fk_Role_id_role"] =! 3): ?> 
                    <li><a class="nav-link nav-anim d-flex align-items-center sidebar-btn" href="cadastro_item.php"><i class="bi bi-plus-circle me-2"></i> Cadastrar Item</a></li>
                <?php endif; ?>
            </ul>
        </li>

        <!-- Movimentações -->
        <?php if ($_SESSION["usuario"]["fk_Role_id_role"] =! 3): ?> 
            <li class="nav-item">
                <a class="nav-link toggle-menu nav-anim d-flex align-items-center sidebar-btn" href="#">
                    <i class="bi bi-arrow-left-right me-2 icon-anim"></i> <span>Movimentações</span>
                    <i class="bi bi-chevron-down ms-auto chevron-icon"></i>
                </a>
                <ul class="nav flex-column sub-menu ms-3 mt-1">
                    <li><a class="nav-link nav-anim d-flex align-items-center sidebar-btn" href="movimentacao.php"><i class="bi bi-plus-circle me-2"></i> Nova Movimentação</a></li>
                    <li><a class="nav-link nav-anim d-flex align-items-center sidebar-btn" href="movimentacoes.php"><i class="bi bi-clock-history me-2"></i> Histórico</a></li>
                </ul>
            </li>
        <?php endif; ?>

        <?php if ($_SESSION["usuario"]["fk_Role_id_role"] =! 3): ?> 
            <li class="nav-item">
                <a class="nav-link toggle-menu nav-anim d-flex align-items-center sidebar-btn" href="#">
                    <i class="bi bi-file-earmark-bar-graph me-2 icon-anim"></i> <span>Relatórios</span>
                    <i class="bi bi-chevron-down ms-auto chevron-icon"></i>
                </a>
                <ul class="nav flex-column sub-menu ms-3 mt-1">
                    <li><a class="nav-link nav-anim d-flex align-items-center sidebar-btn" href="relatorios.php"><i class="bi bi-file-earmark-text me-2"></i> Gerar Relatórios</a></li>
                </ul>
            </li>
        <?php endif; ?>

        <!-- Admin Only -->
        <?php if ($_SESSION["usuario"]["fk_Role_id_role"] == 1): ?> 
            <li class="nav-item">
                <a class="nav-link nav-anim d-flex align-items-center sidebar-btn" href="importar_csv.php">
                    <i class="bi bi-upload me-2 icon-anim"></i> <span>Importar CSV</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link nav-anim d-flex align-items-center sidebar-btn" href="cadastro_categoria.php">
                    <i class="bi bi-tags me-2 icon-anim"></i> <span>Cadastrar Categoria</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link toggle-menu nav-anim d-flex align-items-center sidebar-btn" href="#">
                    <i class="bi bi-person-gear me-2 icon-anim"></i> <span>Painel de Usuários</span>
                    <i class="bi bi-chevron-down ms-auto chevron-icon"></i>
                </a>
                <ul class="nav flex-column sub-menu ms-3 mt-1">
                    <li>
                        <a class="nav-link nav-anim d-flex align-items-center sidebar-btn" href="cadastro_usuario.php">
                            <i class="bi bi-person-plus me-2"></i> Cadastrar Usuário
                        </a>
                    </li>
                    <li>
                        <a class="nav-link nav-anim d-flex align-items-center sidebar-btn" href="usuarios.php">
                            <i class="bi bi-people-fill me-2"></i> Visualizar Usuários
                        </a>
                    </li>
                </ul>
            </li>

        <?php endif; ?>
    </ul>
</div>

<!-- JavaScript para menu expansível e animações -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let toggles = document.querySelectorAll(".toggle-menu");
        toggles.forEach(toggle => {
            toggle.addEventListener("click", function (e) {
                e.preventDefault();
                let subMenu = this.nextElementSibling;
                subMenu.classList.toggle("show");

                let icon = this.querySelector(".chevron-icon");
                if (icon) {
                    icon.classList.toggle("bi-chevron-down");
                    icon.classList.toggle("bi-chevron-up");
                }
            });
        });
    });
</script>

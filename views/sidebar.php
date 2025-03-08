<!-- Sidebar -->
<div class="sidebar">
    <img src="../public/ifsul.png" alt="Logo IFSul" class="logo-sidebar mb-3">
    <h3 class="mb-4"><i class="bi bi-box-seam"></i> AlmoxIF</h3>
    <ul class="nav flex-column">

        <!-- Dashboard -->
        <li class="nav-item">
            <a class="nav-link" href="dashboard.php"><i class="bi bi-house-door"></i> Dashboard</a>
        </li>

        <!-- Estoque (Expansível) -->
        <li class="nav-item">
            <a class="nav-link toggle-menu" href="#"><i class="bi bi-boxes"></i> Estoque <i class="bi bi-chevron-down float-end"></i></a>
            <ul class="nav flex-column sub-menu">
                <li><a class="nav-link" href="estoque.php"><i class="bi bi-eye"></i> Visualizar Estoque</a></li>
                <li><a class="nav-link" href="cadastro_item.php"><i class="bi bi-plus-circle"></i> Cadastrar Item</a></li>
            </ul>
        </li>

        <!-- Movimentações (Expansível) -->
        <li class="nav-item">
            <a class="nav-link toggle-menu" href="#"><i class="bi bi-arrow-left-right"></i> Movimentações <i class="bi bi-chevron-down float-end"></i></a>
            <ul class="nav flex-column sub-menu">
                <li><a class="nav-link" href="movimentacao.php"><i class="bi bi-plus-circle"></i> Nova Movimentação</a></li>
                <li><a class="nav-link" href="movimentacoes.php"><i class="bi bi-clock-history"></i> Histórico de Movimentações</a></li>
            </ul>
        </li>

        <!-- Relatórios (Expansível) -->
        <li class="nav-item">
            <a class="nav-link toggle-menu" href="#"><i class="bi bi-file-earmark-bar-graph"></i> Relatórios <i class="bi bi-chevron-down float-end"></i></a>
            <ul class="nav flex-column sub-menu">
                <li><a class="nav-link" href="relatorios.php"><i class="bi bi-file-earmark-text"></i> Gerar Relatórios</a></li>
            </ul>
        </li>
    </ul>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let toggles = document.querySelectorAll(".toggle-menu");

        toggles.forEach(toggle => {
            toggle.addEventListener("click", function (e) {
                e.preventDefault();
                let subMenu = this.nextElementSibling;
                subMenu.classList.toggle("show");

                // Alterna o ícone da seta
                let icon = this.querySelector("i.bi-chevron-down");
                if (icon) {
                    icon.classList.toggle("bi-chevron-down");
                    icon.classList.toggle("bi-chevron-up");
                }
            });
        });
    });
</script>


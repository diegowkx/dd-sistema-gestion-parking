<div class="header font-primary-family">
    
    <div class="header-content d-flex align-items-center justify-content-between px-4">
        <!-- 7 Círculos decorativos grises distribuidos por toda la topbar -->
        <div class="header-dec-circle-1"></div>
        <div class="header-dec-circle-2"></div>
        <div class="header-dec-circle-3"></div>
        <div class="header-dec-circle-4"></div>
        <div class="header-dec-circle-5"></div>
        <div class="header-dec-circle-6"></div>
        
        <!-- Botón para alternar / colapsar Sidebar -->
        <div class="header-left d-flex align-items-center">
            <button type="button" id="nav_toggle" class="btn btn-link text-dark p-0 me-3 shadow-none border-0">
                <i class="fas fa-bars fs-4"></i>
            </button>
        </div>

        <!-- Perfil / Dropdown de Usuario -->
        <div class="header-right">
            <div class="dropdown">
                <button class="btn btn-link text-dark p-0 dropdown-toggle d-flex align-items-center text-decoration-none shadow-none border-0" 
                        type="button" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user-circle fs-3 text-secondary me-2"></i>
                    <span class="text-bold d-none d-md-inline">
                        <?php echo $_SESSION['usuario_nombre'] ?? 'Usuario'; ?>
                    </span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" aria-labelledby="dropdownUser">a
                    <li>
                        <a class="dropdown-item py-2" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#modalProfile">
                            <i class="fas fa-user me-2 text-primary"></i> Mi Perfil
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item py-2 text-danger" href="?c=auth&a=logout">
                            <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
                        </a>
                    </li>
                </ul>
            </div>
        </div>

    </div>
</div>
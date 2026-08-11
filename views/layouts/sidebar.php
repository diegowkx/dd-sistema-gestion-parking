<div class="deznav font-primary-family">
    <!-- Header del Logo -->
    <div class="nav-header">
        <a href="?c=dashboard" class="brand-logo d-flex align-items-center justify-content-center w-100 h-100 text-decoration-none">
            <img src="<?php echo BASE_URL; ?>/assets/images/logo-full.png" alt="<?php echo APP_NAME; ?>" class="sidebar-logo">
            <img src="<?php echo BASE_URL; ?>/assets/images/logo-image.png" alt="<?php echo APP_NAME; ?>" class="sidebar-logo-icon">
        </a>
    </div>

    <div class="deznav-scroll position-relative">
        <ul class="metismenu px-2" id="menu">

            <?php 
            $currentController = strtolower((string)($_GET['c'] ?? $_GET['controller'] ?? 'dashboard'));

            if (!function_exists('tienePermisoSidebar')) {
                function tienePermisoSidebar(string $permiso): bool {
                    $permisosSession = $_SESSION['permisos'] ?? [];
                    return is_array($permisosSession) && in_array(strtoupper(trim($permiso)), $permisosSession, true);
                }
            }
            ?>

            <!-- 1. DASHBOARD / INICIO -->
            <?php if (tienePermisoSidebar('DASHBOARD')): ?>
                <li class="<?= ($currentController === 'dashboard') ? 'mm-active' : '' ?>">
                    <a class="ai-icon" href="?c=dashboard">
                        <i class="fas fa-th-large"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>
            <?php else: ?>
                <li class="<?= ($currentController === 'dashboard_default' || $currentController === 'inicio') ? 'mm-active' : '' ?>">
                    <a class="ai-icon" href="?c=dashboard_default">
                        <i class="fas fa-home"></i>
                        <span class="nav-text">Inicio</span>
                    </a>
                </li>
            <?php endif; ?>

            <!-- 2. ROLES -->
            <?php if (tienePermisoSidebar('ROLES')): ?>
                <li class="<?= ($currentController === 'roles') ? 'mm-active' : '' ?>">
                    <a class="ai-icon" href="?c=roles">
                        <i class="fas fa-user-shield"></i>
                        <span class="nav-text">Roles</span>
                    </a>
                </li>
            <?php endif; ?>

            <!-- 3. USUARIOS -->
            <?php if (tienePermisoSidebar('USUARIOS')): ?>
                <li class="<?= ($currentController === 'usuarios') ? 'mm-active' : '' ?>">
                    <a class="ai-icon" href="?c=usuarios">
                        <i class="fas fa-users"></i>
                        <span class="nav-text">Usuarios</span>
                    </a>
                </li>
            <?php endif; ?>

            <!-- 4. TIPOS DE PLAZA -->
            <?php if (tienePermisoSidebar('TIPOS_PLAZA')): ?>
                <li class="<?= ($currentController === 'tipos_plaza' || $currentController === 'tipos_plazas') ? 'mm-active' : '' ?>">
                    <a class="ai-icon" href="?c=tipos_plaza">
                        <i class="fas fa-th-list"></i>
                        <span class="nav-text">Tipos de Plaza</span>
                    </a>
                </li>
            <?php endif; ?>

            <!-- 5. PARQUEO -->
            <?php if (tienePermisoSidebar('PARQUEO')): ?>
                <li class="<?= ($currentController === 'parqueo' || $currentController === 'parqueos') ? 'mm-active' : '' ?>">
                    <a class="ai-icon" href="?c=parqueo">
                        <i class="fas fa-parking"></i>
                        <span class="nav-text">Parqueo</span>
                    </a>
                </li>
            <?php endif; ?>

            <!-- 6. TARIFAS -->
            <?php if (tienePermisoSidebar('TARIFAS')): ?>
                <li class="<?= ($currentController === 'tarifas') ? 'mm-active' : '' ?>">
                    <a class="ai-icon" href="?c=tarifas">
                        <i class="fas fa-dollar-sign"></i>
                        <span class="nav-text">Tarifas</span>
                    </a>
                </li>
            <?php endif; ?>

        </ul>
    </div>
</div>
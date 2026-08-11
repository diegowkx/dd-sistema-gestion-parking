<!DOCTYPE html>
<html lang="es">
<head>
    <?php include __DIR__ . '/../layouts/head.php'; ?>
</head>
<body>
    <?php include __DIR__ . '/../layouts/preloader.php'; ?>

    <div id="main-wrapper">
        <?php include __DIR__ . '/../layouts/topbar.php'; ?>
        <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

        <div class="content-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-custom border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                            
                            <!-- ENCABEZADO DE VISTA TIPO HERO (Degradado Rojo para Usuarios) -->
                            <!-- Nota: He mantenido la clase bg-gradient-primary, si necesitas un degradado rojo específico para usuarios, reemplaza por una clase personalizada -->
                            <div class="card-header-hero bg-gradient-primary text-white p-4 position-relative overflow-hidden">
                                <div class="position-relative z-1 d-flex flex-wrap justify-content-between align-items-center gap-3 w-100">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle bg-white-10 p-3 d-flex align-items-center justify-content-center backdrop-blur" style="width: 52px; height: 52px;">
                                            <i class="fas fa-users fs-4 text-white"></i>
                                        </div>
                                        <div>
                                            <span class="badge bg-white-20 text-white rounded-pill px-3 py-1 mb-1 extra-small text-uppercase tracking-wider">Módulo de Seguridad</span>
                                            <h2 class="card-title mb-0 fw-bold fs-4 text-white font-primary-family">Gestión de Usuarios</h2>
                                            <p class="text-white-80 extra-small mb-0">Administra el personal del sistema y sus perfiles de acceso.</p>
                                        </div>
                                    </div>
                                    
                                    <button type="button" class="btn btn-light rounded-pill px-4 py-2-5 text-slate-800 fw-bold shadow-sm d-inline-flex align-items-center gap-2 btn-nuevo-usuario">
                                        <i class="fas fa-plus text-primary-custom"></i>
                                        <span>Nuevo Usuario</span>
                                    </button>
                                </div>
                                <!-- Elementos decorativos de fondo -->
                                <div class="header-bg-circle-1"></div>
                                <div class="header-bg-circle-2"></div>
                            </div>

                            <!-- PESTAÑAS DE NAVEGACIÓN Y TABLA -->
                            <div class="card-body p-4">
                                <!-- Pestañas de estado de usuarios -->
                                <div class="w-100 text-start mb-4">
                                    <ul class="nav nav-pills nav-pills-custom gap-2 p-1 bg-slate-50 rounded-pill d-inline-flex border border-slate-100" id="pills-tab-usuarios" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link tab-estado active rounded-pill px-4 py-2 fw-semibold fs-7 transition-all d-flex align-items-center gap-2" data-estado="ACTIVO" type="button">
                                                <i class="fas fa-user-check"></i>
                                                <span>Usuarios Activos</span>
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link tab-estado rounded-pill px-4 py-2 fw-semibold fs-7 transition-all d-flex align-items-center gap-2" data-estado="INACTIVO" type="button">
                                                <i class="fas fa-user-slash"></i>
                                                <span>Usuarios Inactivos</span>
                                            </button>
                                        </li>
                                    </ul>
                                </div>

                                <!-- Tabla DataTables -->
                                <div class="table-responsive">
                                    <table id="datatable_usuarios" class="table table-hover align-middle w-100">
                                        <thead>
                                            <tr>
                                                <th>Documento</th>
                                                <th>Nombres y Apellidos</th>
                                                <th>Usuario</th>
                                                <th>Rol</th>
                                                <th>Correo</th>
                                                <th>Estado</th>
                                                <th class="text-center">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include __DIR__ . '/../layouts/footer.php'; ?>
    </div>

    <?php include __DIR__ . '/../layouts/scripts.php'; ?>
    <?php include __DIR__ . '/form_modal.php'; ?>
    
    <script src="<?php echo BASE_URL; ?>/views/usuarios/usuario.js"></script>
</body>
</html>
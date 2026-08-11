<!DOCTYPE html>
<html lang="es">
<head>
    <?php include __DIR__ . '/../layouts/head.php'; ?>
</head>
<body class="font-primary-family">

    <div id="main-wrapper">

        <!-- Sidebar Fija -->
        <?php include __DIR__ . '/../layouts/sidebar.php'; ?>
        
        <!-- Topbar Fija -->
        <?php include __DIR__ . '/../layouts/topbar.php'; ?>

        <!-- Cuerpo del Módulo -->
        <div class="content-body">
            <div class="container-fluid">
                
                <!-- Encabezado de la Vista Ampliado -->
                <div class="row mb-4 align-items-center">
                    <div class="col-md-6">
                        <h1 class="text-bold mb-0 fs-2">Panel de Control Gerencial</h1>
                    </div>
                    <div class="col-md-6 text-md-end mt-2 mt-md-0">
                        <span class="fs-5 text-bold text-accent">Dashboard</span>
                    </div>
                </div> 

                <!-- ÁREA DE CONTENIDO -->
                <div class="row">
                    <div class="col-12">
                        <!-- Componentes principales -->
                    </div>
                </div>

            </div>
        </div>

        <!-- Modal de Auditoría de IP -->
        <div class="modal fade" id="modalIpDetalle" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-bold">Detalle del Registro de IP</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <strong>ID Registro:</strong> <span id="det_ip_id">-</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <strong>Username:</strong> <span id="det_ip_usuario">-</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <strong>Nombre Completo:</strong> <span id="det_ip_nombre">-</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <strong>Correo:</strong> <span id="det_ip_correo">-</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <strong>Rol:</strong> <span id="det_ip_rol" class="badge bg-dark">-</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <strong>Dirección IP:</strong> <span id="det_ip_address" class="text-bold text-dark">-</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <strong>Fecha de Acceso:</strong> <span id="det_ip_fecha">-</span>
                            </li>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <?php include __DIR__ . '/../layouts/footer.php'; ?>

    </div>

    <?php include __DIR__ . '/../layouts/scripts.php'; ?>
    <script src="<?php echo BASE_URL; ?>/views/dashboard/dashboard.js"></script>
</body>
</html>
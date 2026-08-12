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

                <!-- Encabezado de la Vista -->
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

                    <!-- TARJETAS KPI -->
                    <div class="col-12">
                        <div class="row g-3 mb-4" id="kpi-cards">
                            <div class="col-sm-6 col-xl-3">
                                <div class="card card-custom border-0 shadow-sm rounded-4 h-100">
                                    <div class="card-body p-4 d-flex align-items-center gap-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;background-color:#d9383a1a;">
                                            <i class="fas fa-car fs-5" style="color:#d9383a;"></i>
                                        </div>
                                        <div>
                                            <div class="extra-small text-muted text-uppercase">Vehículos Ahora</div>
                                            <div class="fs-4 fw-bold" id="kpi_vehiculos_activos">—</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xl-3">
                                <div class="card card-custom border-0 shadow-sm rounded-4 h-100">
                                    <div class="card-body p-4 d-flex align-items-center gap-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;background-color:#19875410;">
                                            <i class="fas fa-square-parking fs-5 text-success"></i>
                                        </div>
                                        <div>
                                            <div class="extra-small text-muted text-uppercase">Espacios Disponibles</div>
                                            <div class="fs-4 fw-bold" id="kpi_espacios_disponibles">—</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xl-3">
                                <div class="card card-custom border-0 shadow-sm rounded-4 h-100">
                                    <div class="card-body p-4 d-flex align-items-center gap-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;background-color:#0d6efd10;">
                                            <i class="fas fa-sack-dollar fs-5 text-primary"></i>
                                        </div>
                                        <div>
                                            <div class="extra-small text-muted text-uppercase">Ingresos de Hoy</div>
                                            <div class="fs-4 fw-bold" id="kpi_ingresos_hoy">—</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xl-3">
                                <div class="card card-custom border-0 shadow-sm rounded-4 h-100">
                                    <div class="card-body p-4 d-flex align-items-center gap-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;background-color:#fd7e1410;">
                                            <i class="fas fa-coins fs-5" style="color:#fd7e14;"></i>
                                        </div>
                                        <div>
                                            <div class="extra-small text-muted text-uppercase">Ingresos del Mes</div>
                                            <div class="fs-4 fw-bold" id="kpi_ingresos_mes">—</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- OCUPACIÓN POR TIPO DE PLAZA -->
                    <div class="col-12 col-lg-5 mb-4">
                        <div class="card card-custom border-0 shadow-sm rounded-4 h-100">
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-1">Ocupación por Tipo de Plaza</h5>
                                <p class="extra-small text-muted mb-4">Espacios ocupados vs. capacidad total, por tipo activo.</p>
                                <div id="ocupacion-por-tipo">
                                    <p class="text-muted extra-small mb-0">Cargando...</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- INGRESOS ÚLTIMOS 7 DÍAS -->
                    <div class="col-12 col-lg-7 mb-4">
                        <div class="card card-custom border-0 shadow-sm rounded-4 h-100">
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-1">Ingresos — Últimos 7 Días</h5>
                                <p class="extra-small text-muted mb-4">Suma de cobros registrados en tarifas_cobros por día.</p>
                                <canvas id="chart_ingresos_7_dias" height="140"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- HISTORIAL DE IPS (existente) -->
                    <div class="col-12">
                        <div class="card card-custom border-0 shadow-sm rounded-4">
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-1">Auditoría de Accesos</h5>
                                <p class="extra-small text-muted mb-4">Historial de IPs registradas por usuario.</p>
                                <div class="table-responsive">
                                    <table id="datatable_ips" class="table table-hover align-middle w-100">
                                        <thead>
                                            <tr>
                                                <th>Usuario</th>
                                                <th>Nombre Completo</th>
                                                <th>Rol</th>
                                                <th>IP</th>
                                                <th>Fecha de Acceso</th>
                                                <th class="text-center">Detalle</th>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/views/dashboard/dashboard.js"></script>
</body>
</html>
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

                            <!-- ENCABEZADO DE VISTA TIPO HERO -->
                            <div class="card-header-hero bg-gradient-primary text-white p-4 position-relative overflow-hidden">
                                <div class="position-relative z-1 d-flex flex-wrap justify-content-between align-items-center gap-3 w-100">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle bg-white-10 p-3 d-flex align-items-center justify-content-center backdrop-blur" style="width: 52px; height: 52px;">
                                            <i class="fas fa-car fs-4 text-white"></i>
                                        </div>
                                        <div>
                                            <span class="badge bg-white-20 text-white rounded-pill px-3 py-1 mb-1 extra-small text-uppercase tracking-wider">Módulo de Parqueo</span>
                                            <h2 class="card-title mb-0 fw-bold fs-4 text-white font-primary-family">Control de Parqueo</h2>
                                            <p class="text-white-80 extra-small mb-0">Tickets activos, ingresos y salidas de vehículos.</p>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-outline-light rounded-pill px-4 py-2-5 fw-bold shadow-sm d-inline-flex align-items-center gap-2 btn-ticket-perdido">
                                            <i class="fas fa-magnifying-glass"></i>
                                            <span>Ticket Perdido</span>
                                        </button>
                                        <button type="button" class="btn btn-light rounded-pill px-4 py-2-5 text-slate-800 fw-bold shadow-sm d-inline-flex align-items-center gap-2 btn-nuevo-ingreso">
                                            <i class="fas fa-plus text-primary-custom"></i>
                                            <span>Nuevo Ingreso</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="header-bg-circle-1"></div>
                                <div class="header-bg-circle-2"></div>
                            </div>

                            <!-- TABLA DE TICKETS ACTIVOS -->
                            <div class="card-body p-4">
                                <div class="table-responsive">
                                    <table id="datatable_parqueo" class="table table-hover align-middle w-100">
                                        <thead>
                                            <tr>
                                                <th>Código</th>
                                                <th>Placa</th>
                                                <th>Tipo</th>
                                                <th>Ingreso</th>
                                                <th>Tiempo Transcurrido</th>
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

    <script src="<?php echo BASE_URL; ?>/views/parqueo/parqueo.js"></script>
</body>
</html>

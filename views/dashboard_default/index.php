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
                        <h1 class="text-bold mb-0 fs-2">Panel de Inicio</h1>
                        <p class="text-muted small mb-0 mt-1">
                            Hola, <span class="text-bold text-dark"><?php echo htmlspecialchars(trim(($_SESSION['user_nombres'] ?? '') . ' ' . ($_SESSION['user_apellidos'] ?? ''))); ?></span> 
                            (Rol: <span class="badge bg-secondary"><?php echo htmlspecialchars($_SESSION['user_rol'] ?? ''); ?></span>)
                        </p>
                    </div>
                    <div class="col-md-6 text-md-end mt-2 mt-md-0">
                        <span class="fs-5 text-bold text-accent">Dashboard</span>
                    </div>
                </div>

                <!-- ÁREA DE CONTENIDO -->
                <div class="row">

                    <!-- TARJETA DE BIENVENIDA -->
                    <div class="col-12 mb-4">
                        <div class="card card-custom border-0 shadow-sm rounded-4 bg-white">
                            <div class="card-body p-4 p-md-5">
                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                                    <div>
                                        <h3 class="text-dark text-bold mb-2">¡Bienvenido al Sistema!</h3>
                                        <p class="mb-0 text-muted fs-6" style="max-width: 700px;">
                                            Utiliza el menú lateral para navegar y realizar las operaciones correspondientes a tu rol dentro de la plataforma.
                                        </p>
                                    </div>
                                    <div class="p-3 rounded-circle shadow-sm" style="width: 70px; height: 70px; display: flex; align-items: center; justify-content: center; background-color: #19875410; color: #198754;">
                                        <i class="fas fa-concierge-bell fs-2"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <!-- Footer -->
        <?php include __DIR__ . '/../layouts/footer.php'; ?>

    </div>

    <?php include __DIR__ . '/../layouts/scripts.php'; ?>
    <script src="<?php echo BASE_URL; ?>/views/dashboard_default/dashboard_default.js"></script>
</body>
</html>
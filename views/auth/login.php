<!DOCTYPE html>
<html lang="es">
<head>
    <?php include __DIR__ . '/../layouts/head.php'; ?>

    <!-- CSS Exclusivo del Login -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/custom/login.css">
</head>
<body class="font-primary-family login-body">

    <div class="login-split-container">
        <!-- Columna Izquierda: Diagonal Decorativa -->
        <div class="login-banner-side">
            <div class="login-banner-overlay">
                <div class="banner-text">
                    <h2 class="text-bold"><?php echo APP_NAME; ?></h2>
                    <p>Sistema de Gestión Integrada</p>
                </div>
            </div>
        </div>

        <!-- Columna Derecha: Formulario de Login -->
        <div class="login-form-side">
            <div class="login-card-box">
                <div class="login-header text-center">
                    <a href="index.php" class="brand-logo text-bold">
                        <?php echo APP_NAME; ?>
                    </a>
                    <h4 class="login-title text-bold">Iniciar Sesión</h4>
                </div>

                <form id="loginForm" action="<?php echo BASE_URL; ?>/?c=auth&a=process_login" method="POST" class="login-form">
                    <!-- Campo de Correo Electrónico -->
                    <div class="form-group mb-3">
                        <label for="emailInput" class="text-bold mb-1">Correo electrónico</label>
                        <input type="email" class="form-control custom-input" id="emailInput" name="email" placeholder="tucorreo@example.com" required autocomplete="email">
                    </div>

                    <!-- Campo de Contraseña Encapsulado -->
                    <div class="form-group mb-4">
                        <label for="passwordInput" class="text-bold mb-1">Contraseña</label>
                        <div class="password-wrapper">
                            <input type="password" class="form-control custom-input" id="passwordInput" name="password" placeholder="••••••••" required autocomplete="current-password">
                            <button type="button" id="togglePassword" class="password-toggle-btn" tabindex="-1">
                                <i class="fa fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-action">
                        <button type="submit" class="btn btn-custom-submit text-bold">Ingresar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 1. Scripts globales (jQuery, DataTables, SweetAlert2, Config, Profile JS) -->
    <?php include __DIR__ . '/../layouts/scripts.php'; ?>

    <!-- 2. Alertas dinámicas con SweetAlert2 -->
    <?php if (isset($error) && $error): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: '¡Acceso Denegado!',
            text: '<?php echo addslashes($error); ?>',
            confirmButtonColor: (typeof AppConfig !== 'undefined' && AppConfig.sweetAlertDefaults) 
                ? AppConfig.sweetAlertDefaults.confirmButtonColor 
                : '#d9383a',
            background: '#ffffff',
            customClass: { popup: 'shadow-lg' },
            heightAuto: false
        }).then(() => {
            window.history.replaceState({}, document.title, window.location.pathname);
        });
    </script>
    <?php endif; ?>

    <?php if (isset($success) && $success): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Completado!',
            text: '<?php echo addslashes($success); ?>',
            timer: 3000,
            showConfirmButton: false,
            timerProgressBar: true,
            heightAuto: false
        }).then(() => {
            window.history.replaceState({}, document.title, window.location.pathname);
        });
    </script>
    <?php endif; ?>

    <!-- 3. JS de la interfaz general -->
    <script src="<?php echo BASE_URL; ?>/assets/js/custom/login.js"></script>

    <!-- 4. Script de login -->
    <script src="<?php echo BASE_URL; ?>/views/auth/login.js?v=<?php echo time(); ?>"></script>
</body>
</html>
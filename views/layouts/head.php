<!--==================================================-->
<!--                    META                          -->
<!--==================================================-->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo isset($title) ? $title : 'Sistema de Gestión'; ?> | <?php echo APP_NAME; ?></title>
<link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>/assets/images/favicon.png">

<!--=================================================-->
<!--                    CSS BASE Y VENDORS            -->
<!--==================================================-->

<!-- Reset y estilos base globales -->
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/default/default.css">

<!-- Configuración Global (Fuentes, variables CSS e IDs de tipografía) -->
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/config.css">

<!-- Bootstrap 5 CSS Local -->
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/bootstrap.min.css">

<!-- Iconos FontAwesome 6 Local -->
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/fontawesome.min.css">

<!-- Componentes y Layout Global -->
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/custom/sidebar.css">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/custom/components.css">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/animations/anim_sidebar.css">

<!-- DataTables CSS Global -->
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/custom/datatables.css?v=<?php echo time(); ?>">

<!-- DataTables CSS Local -->
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/jquery.dataTables.min.css">

<!-- SweetAlert2 CSS Local -->
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/sweetalert2.min.css">


<!--==================================================-->
<!--     CARGA CONDICIONAL DE CSS POR MÓDULO ACTUAL   -->
<!--==================================================-->
<?php
// Detecta el controlador actual
$controllerName = strtolower((string)($_GET['c'] ?? $_GET['controller'] ?? 'dashboard'));

// 1. Carga automática si existe un CSS que coincide con el controlador (ej. dashboard.css o anim_dashboard.css)
$moduleCss = __DIR__ . '/../../assets/css/custom/' . $controllerName . '.css';
$moduleAnimCss = __DIR__ . '/../../assets/css/animations/anim_' . $controllerName . '.css';

if (file_exists($moduleCss)) {
    echo '<link rel="stylesheet" href="' . BASE_URL . '/assets/css/custom/' . $controllerName . '.css?v=' . filemtime($moduleCss) . '">' . PHP_EOL;
}

if (file_exists($moduleAnimCss)) {
    echo '<link rel="stylesheet" href="' . BASE_URL . '/assets/css/animations/anim_' . $controllerName . '.css?v=' . filemtime($moduleAnimCss) . '">' . PHP_EOL;
}

// 2. Si la vista solicita CSS adicionales personalizados mediante un array $extra_css
if (isset($extra_css) && is_array($extra_css)) {
    foreach ($extra_css as $cssFile) {
        $filePath = __DIR__ . '/../../assets/css/' . $cssFile;
        if (file_exists($filePath)) {
            echo '<link rel="stylesheet" href="' . BASE_URL . '/assets/css/' . $cssFile . '?v=' . filemtime($filePath) . '">' . PHP_EOL;
        }
    }
}
?>
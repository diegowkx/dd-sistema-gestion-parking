<!doctype html>
<html lang="es" class="light-theme">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- loader-->
  <link href="assets/css/pace.min.css" rel="stylesheet" />
  <script src="assets/js/pace.min.js"></script>

  <!--plugins-->
  <link href="assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />

  <!-- CSS Files -->
  <link href="assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/bootstrap-extended.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
  <link href="assets/css/icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <title>Iniciar sesión | ParkQuick Zone</title>
  <style>
  html, body, .wrapper {
    height: 100%;
    min-height: 100vh;
  }
  .row.g-0.m-0 {
    min-height: 100vh;
  }
  .login-cover-wrapper {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .login-cover-img {
    height: 100% !important;
    min-height: 100vh !important;
  }
</style>
</head>

<body class="bg-white">

  <!--start wrapper-->
  <div class="wrapper">
    <div class="">
      <div class="row g-0 m-0">
        <!-- Columna Izquierda: Formulario sin logos inventados -->
        <div class="col-xl-6 col-lg-12">
          <div class="login-cover-wrapper">
            <div class="card shadow-none">
              <div class="card-body">
                <div class="text-center">
                  <h4>Iniciar Sesión</h4>
                </div>

                <form id="loginForm" action="?c=auth&a=process_login" method="POST" class="form-body row g-3">
                  <div class="col-12">
                    <label for="emailInput" class="form-label">Correo</label>
                    <input type="email" class="form-control" id="emailInput" name="email" placeholder="Email" required autocomplete="email">
                  </div>

                  <div class="col-12">
                    <label for="passwordInput" class="form-label">Contraseña</label>
                    <div class="position-relative">
                      <input type="password" class="form-control" id="passwordInput" name="password" placeholder="Password" style="padding-right: 45px;" autocomplete="current-password" required>
                      <span id="togglePassword" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #6e6e6e; z-index: 10;">
                        <i class="fa fa-eye" id="toggleIcon"></i>
                      </span>
                    </div>
                  </div>


                  <div class="col-12 col-lg-12">
                    <div class="d-grid">
                      <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
                    </div>
                  </div>

                </form>

              </div>
            </div>
          </div>
        </div>

        <!-- Columna Derecha Identica a la plantilla -->
        <div class="col-xl-6 col-lg-12">
          <div class="position-absolute top-0 h-100 d-xl-block d-none login-cover-img">
            <div class="text-white p-5 w-100">

            </div>
          </div>
        </div>

      </div>
      <!--end row-->
    </div>
  </div>
  <!--end wrapper-->

  <!-- Scripts -->
  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/js/bootstrap.bundle.min.js"></script>
  <script src="views/auth/login.js?v=1786394631"></script>

</body>

</html>
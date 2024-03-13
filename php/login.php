<?php

require '../config/config.php';
require '../config/database.php';
require '../clases/userFunciones.php';

$db = new Database();
$con = $db->conectar();

$errors = [];

if (!empty($_POST)) {


    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);

    if (esNulo([$usuario, $password])) {
        $errors[] = "Deve llenar todos los campos.";
    }

    if (count($errors) == 0) {

        $errors[] =  login($usuario, $password, $con);
    }
}


?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--Favicon-->
    <link rel="shortcut icon" href="../img/sicovLogo.ico">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/line-icons.css">
    <link rel="stylesheet" href="../css/style.css">
    <title>SICOV | Login</title>
    <style>
        .form-login {
            max-width: 350px;
        }
    </style>
</head>

<body class="modulos">

    <!--Header-->
    <header style="background-color: white;">
        <div class="navbar navbar-expand-lg navbar-ligh ">
            <div class="container">
                <a href="index.php" class="navbar-brand col-">
                    <strong><a class="navbar-brand" href="../index.php"><img class="logo" src="../img/logoSicov.png"> <span style=" font-family: 'nasalization', sans-serif;">SICOV</span></a></strong>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <!-- Main -->
    <br>
    <main class="form-login m-auto pt-4">
        <h2>Iniciar sesión</h2>
        <hr>
        <?php mostrarErrors($errors); ?>
        <form class="row g-3" action="login.php" method="post" autocomplete="off">

            <div class="form-floating">
                <input class="form-control" type="text" name="usuario" id="usuario" placeholder="Usuario" require>
                <label for="usuario">Usuario</label>
            </div>

            <div class="form-floating">
                <input class="form-control" type="password" name="password" id="password" placeholder="Contraseña" require>
                <label for="password">Contraseña</label>
            </div>

            <div class="d-grid gap-3 col-12">
                <button class="btn btn-primary" type="submit">Ingresar</button>
            </div>
            <hr>
        </form>
    </main>


    <!--Bootstrap-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="js/app.js"></script>
</body>

</html>
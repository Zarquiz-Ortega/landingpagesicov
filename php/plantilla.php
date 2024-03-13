<?php

require '../config/config.php';
require '../config/database.php';

$db = new Database();
$con = $db->conectar();

$errors = [];

$sql = $con->prepare("SELECT id, nombre, desarollo FROM modulos WHERE estado=1");
$sql->execute();
$resuldado = $sql->fetchAll(PDO::FETCH_ASSOC);

?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--Favicon-->
    <link rel="shortcut icon" href="../img/sicovLogo.ico">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/line-icons.css">
    <link rel="stylesheet" href="../css/style.css">
    <title>SICOV | Modulos</title>
    <style>
        main > .container {
            padding: 30px 0;
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
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class='bx bx-menu'></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                     <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                        </li>
                    </ul>
                    <a href="checkout.php" class="btn btn-success btn-md">
                        <i class='bx bx-cart bx-sm'></i><span id="num_cart" class="badge bg-dark"><?php echo $num_cart;?></span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main -->
    <main>
        <div class="container">
        <div class="row">
                <did class="col-4">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <th>
                                    <h2>Prospecto</h2>
                                </th>
                            </thead>
                            <tbody>
                                <td>
                                    <div class="card text-center">
                                        <div class="card-header" style="background: #552AD2; color: white;">
                                            <b>duda o cotizacion</b>
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title">Nombre de la empresa</h5>
                                            <p class="card-text">comentarios o consultas</p>
                                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                <!-- Button trigger modal -->
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                    Detalles
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-footer text-muted">
                                            fecha xx/xx/xxxx
                                        </div>
                                    </div>
                                </td>
                            </tbody>
                        </table>
                    </div>
                </did>
                <did class="col-4">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <th>
                                    <h2>Primer contacto</h2>
                                </th>
                            </thead>
                            <tbody>
                                <td>
                                    <div class="card text-center">
                                        <div class="card-header" style="background: #DC204B; color: white;">
                                            <b>duda o cotizacion</b>
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title">Nombre de la empresa</h5>
                                            <p class="card-text">comentarios o consultas</p>
                                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                <!-- Button trigger modal -->
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                    Detalles
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-footer text-muted">
                                            fecha xx/xx/xxxx
                                        </div>
                                    </div>
                                </td>
                            </tbody>
                        </table>
                    </div>
                </did>
                <did class="col-4">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <th>
                                    <h2>Venta</h2>
                                </th>
                            </thead>
                            <tbody>
                                <td>
                                    <div class="card text-center">
                                        <div class="card-header" style="background: #12A552; color: white;">
                                            <b>duda o cotizacion</b>
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title">Nombre de la empresa</h5>
                                            <p class="card-text">comentarios o consultas</p>
                                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                <!-- Button trigger modal -->
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                    Detalles
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-footer text-muted">
                                            fecha xx/xx/xxxx
                                        </div>
                                    </div>
                                </td>
                            </tbody>
                        </table>
                    </div>
                </did>
            </div>
        </div>
    </main>

    <!--Bootstrap-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="js/app.js"></script>
</body>

</html>
<?php

require '../config/config.php';
require '../config/database.php';

$db = new Database();
$con = $db->conectar();

$errors = [];

if (isset($_SESSION['user']['user_name'])) {
    
    $id_customers = $_GET['id'];

    $sql = $con->prepare("SELECT nombres, apellidos, telefono, nom_empresa, email, mensaje, modulos, asunto, estado, fecha  FROM customers WHERE id LIKE ? LIMIT 1");
    $sql->execute([$id_customers]);
    $row = $sql->fetch(PDO::FETCH_ASSOC);

    $nombre = $row['nombres'];
    $apellido = $row['apellidos'];
    $telefono = $row['telefono'];
    $codigo_pais = substr($telefono, 0, 2);
    $codigo_area = substr($telefono, 2, 4);
    $numero = substr($telefono, 6);
    $telefono_formateado = "($codigo_pais) $codigo_area $numero";
    $nom_empresa = $row['nom_empresa'];
    $email = $row['email'];
    $mensaje = $row['mensaje'];
    $modulo = $row['modulos'];
    $modulos = explode(",", trim($modulo, ","));
    $asunto = $row['asunto'];
    $estado = $row['estado'];
    $fecha = $row['fecha'];
} else {
    header("location: login.php");
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
    <title>SICOV | Customers</title>
    <style>
        main>.container {
            padding: 30px 0;
        }
    </style>
</head>

<body>

    <!--Header-->
    <header style="background-color: white;">
        <div class="navbar navbar-expand-lg navbar-ligh ">
            <div class="container">
                <a href="index.php" class="navbar-brand col-">
                    <strong><a class="navbar-brand" href="../index.php"><img class="logo" src="../img/logoSicov.png"> &nbsp; <span style=" font-family: 'nasalization', sans-serif;">SICOV</span></a></strong>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <i class='bx bx-menu'></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                        </li>
                    </ul>
                    <div class="dropdown">
                        <button class="btn btn-success btn-sm dropdown-toggle" type="button" id="btn_session" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class='bx bx-user-circle bx-sm'></i> <b class="h6"><?php echo $_SESSION['user']['user_name'] ?></b>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="btn_session">
                            <li><a class="dropdown-item" href="logout.php">Cerrar sesión</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main -->
    <main>
        <div class="container">
            <div class="row">
                <div class="col text-end">
                    <a class="btn btn-danger" href="#">Canselar venta</a><br>
                </div>
            </div>
            <div class="row p-3">
                <div class="col-12">
                    <p class="h3 bg-dark text-white">Datos del cliente</p>
                </div>
                <div class="row">
                    <div class="col-5">
                        <p class="fs-5">
                            <span class="fw-bold">Nombre: </span><?php echo $nombre; ?> <?php echo $apellido; ?>
                        </p>
                    </div>
                    <div class="col-7">
                        <p class="fs-5">
                            <span class="fw-bold">Telefono: </span><?php echo $telefono_formateado; ?>&nbsp;&nbsp;
                            <span class="fw-bold">Correo: </span><?php echo $email; ?><br>
                            <span class="fw-bold">Nombre de la empresa: </span><?php echo $nom_empresa; ?>
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p class="h3 bg-dark text-white"><?php echo $asunto; ?></p>
                    </div>
                </div>
                <div class="row">
                    <?php if ($asunto == 'Cotización') { ?>
                        <div class="col-6">
                            <p>
                                <span class="fw-bold">Modulos de interes</span><br>
                            <ul class="list-group list-group-flush align-items-start ">
                                <?php $count = count($modulos);
                                for ($i = 0; $i < $count; $i++) { ?>

                                    <li class="list-group-item"> <?php echo $modulos[$i]; ?></li>
                                <?php } ?>
                            </ul>
                            </p>
                        </div>
                        <div class="col-6">
                            <p>
                               
                            </p>
                        </div>
                    <?php } else { ?>
                        <div class="col">
                            <p>
                                <span class="fw-bold">Dudas o comentarios</span><br>
                                <?php echo $mensaje; ?>
                            </p>
                        </div>
                    <?php } ?>
                </div>
                <div class="row">
                    <div class="col">
                        <p class="h4 bg-dark text-white">Agregar un cometario</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col text-center">
                        <textarea class="form-control form-control-sm" rows="5" name="comentarios" id="comentarios" placeholder="Sin comentarios"></textarea><br>
                    </div>
                </div>
                <div class="row">
                    <div class="col text-center">
                        <a class="btn btn-success" href="#">Pasar al siguien paso</a>
                    </div>
                </div>

            </div>
        </div>
    </main>



    <!--Bootstrap-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <script src="../js/app.js"></script>



</body>

</html>
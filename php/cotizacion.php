<?php
require '../config/config.php';
require '../config/database.php';
require '../clases/userFunciones.php';

$db = new Database();
$con = $db->conectar();

$errors = [];
$mensajes = [];
$modulos = '';

if (!empty($_POST)) {

    $nombres = trim($_POST['nombres']);
    $apellidos = trim($_POST['apellidos']);
    $telefono = $_POST['telefono'];
    $nom_empresa = trim($_POST['nom_empresa']);
    $email = trim($_POST['email']);
    $mensaje = trim($_POST['mensaje']);
    $modulos = trim($_POST['modulos']);
    $asunto = trim($_POST['asunto']);
    $estatus = trim($_POST['estatus']);
    $accion = trim($_POST['accion']);
    $fecha = obtenerFecha();

    if ($mensaje == null) {
        $mensaje = 'Sin comentarios';
    }

    if (esNulo([$nombres, $apellidos, $telefono, $nom_empresa, $email])) {
        $errors[] = "Debe llenar todos los campos.";
    } else {
        if (!esEmail($email)) {
            $errors[] = "La dirección de correo $email no es valida.";
        }
        if (esTelefono($telefono)) {
            $errors[] = "El numero de telefono ( $telefono ) no es valido.";
        } else {
            if (!esNacional($telefono))
                $errors[] = "El numero de telefono ( $telefono ) debe empear con 56 0 55.";
        }

        if (count($errors) == 0) {
            $id = registraCustomers([$nombres, $apellidos, $telefono, $nom_empresa, $email], $con);
            if ($id > 0) {

                if (reguistroMensaje([$mensaje, $modulos, $asunto, $fecha, $id, 1], $con)) {
                    session_destroy();
                    header("location: ../index.php?cotizacion");
                }
            } else {
                $errors[] = "ocurrio un error al generara el reguistro";
            }
        }
    }
}

$productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;
//print_r($_SESSION);  //imprime el array del carrito
$lista_carrito = array();

if ($productos != null) {

    foreach ($productos as $clave => $cantidad) {
        $sql = $con->prepare("SELECT nombre, $cantidad AS cantidad FROM modulos WHERE 
        id=? AND estado=1");
        $sql->execute([$clave]);
        $lista_carrito[] = $sql->fetch(PDO::FETCH_ASSOC);
    }
} else {
    header("Location: ../index.php");
    exit;
}



?>

<!DOCTYPE html>
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
    <title>SICOV | Checkout</title>
    <style>
        main>.container {
            padding: 30px 0;
        }
    </style>
</head>

<body>

    <!--Header-->
    <header>
        <div class="navbar navbar-expand-lg navbar-ligh ">
            <div class="container">
                <a href="modulos.php" class="navbar-brand col-">
                    <strong><a class="navbar-brand" href="modulos.php"><img class="logo" src="../img/logoSicov.png"> &nbsp; <span style=" font-family: 'nasalization', sans-serif;">SICOV</span></a></strong>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse col-6" id="navbarHeader">
                    <ul class="navbar-nav col-6 me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                        </li>
                    </ul>
                    <a href="checkout.php" class="btn btn-success btn-md">
                        <i class='bx bx-cart bx-sm'></i><span id="num_cart" class="badge bg-dark"><?php echo $num_cart; ?></span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="row">
                <div class="col-8">
                    <div class="fondo">
                        <div class="sombra">
                            <h4>Solicitar Cotización</h4>
                            <div>
                                <p>Ingrese sus datos</p>
                                <?php mostrarErrors($errors); ?>
                                <form class="row g-3" action="cotizacion.php" method="post">
                                    <?php foreach ($lista_carrito as $producto) {
                                        $nombre = $producto['nombre'];
                                        if ($modulos == null) {
                                            $modulos = $nombre;
                                        } else {
                                            $modulos .= ',' . $nombre;
                                        }
                                    } ?>
                                    <input type="hidden" name="asunto" id="asunto" value="Cotización">
                                    <input type="hidden" name="estatus" id="modluos" value="1">
                                    <input type="hidden" name="accion" id="accion" value="cotizacion">
                                    <input type="hidden" name="modulos" id="modulos" value="<?php echo $modulos ?>">
                                    <div class="col-md-6 form-floating">
                                        <input type="text" placeholder="Nombre" name="nombres" id="nombres" class="form-control rounded-3 border border-success">
                                        <label for="nombres"><span class="text-danger">*</span> Nombres</label>
                                    </div>
                                    <div class="col-md-6 form-floating">
                                        <input type="text" name="apellidos" id="apellidos" class="form-control rounded-3 border border-success" placeholder="Apellidos">
                                        <label for="Apellidos"><span class="text-danger">*</span> Apellidos</label>
                                    </div>
                                    <div class="col-md-6 form-floating">
                                        <input type="text" placeholder="Telefono" name="telefono" id="telefono" class="form-control rounded-3 border border-success">
                                        <label for="apellidos"><span class="text-danger">*</span> Telefono</label>
                                    </div>
                                    <div class="col-md-6 form-floating">
                                        <input type="text" placeholder="Nombre de la empresa" name="nom_empresa" id="nom_empresa" class="form-control rounded-3 border border-success">
                                        <label for="apellidos"><span class="text-danger">*</span> Nombre de la empresa</label>
                                    </div>
                                    <div class="col-md-12 form-floating">
                                        <input type="email" placeholder="Correro electronico" name="email" id="emal" class="form-control rounded-3 border border-success">
                                        <label for="apellidos"><span class="text-danger">*</span> Correro Electrónico</label>
                                    </div>
                                    <div class="col-md-12 form-floating">
                                        <textarea class="form-control rounded-3 border border-success" placeholder="Comentarios o dudas" name="mensaje" id="mensaje" cols="20" rows="10"></textarea>
                                        <label for="apellidos">Comentarios o dudas</label>
                                    </div>
                                    <i><b>Nota: </b> Los campos con asterisco (<span class="text-danger">*</span>) son obligatorios </i>
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-outline-success btn-lg ">Solicitar Cotización</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>
                                        <p class="h5">Modulos solicitados</p>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($lista_carrito == null) {
                                    echo '<tr><td colspan=5 class="text-center"><b>Lista vacia</b></td></tr>';
                                } else {

                                    foreach ($lista_carrito as $producto) {
                                        $nombre = $producto['nombre'];
                                ?>
                                        <tr>
                                            <td>
                                                <p class="h5"><?php echo $nombre; ?></p>
                                            </td>
                                        </tr>
                                    <?php  } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } ?>
                </div>
            </div>
        </div>


        <!--footer-->
        <footer class="py-4">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-0">Copyright © 20xx-20xx.</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <div>
                            <a href="#"><i class='bx bxl-facebook-circle'></i></a>
                            <a href="#"><i class='bx bxl-twitter'></i></a>
                            <a href="#"><i class='bx bxl-dribbble'></i></a>
                            <a href="#"><i class='bx bxl-instagram'></i></a>
                            <a href="#"><i class='bx bxl-github'></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </main>



    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>

</html>
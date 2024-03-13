<?php

require '../config/config.php';
require '../config/database.php';

$db = new Database();
$con = $db->conectar();

$id = isset($_GET['id']) ? $_GET['id'] : '';
$token = isset($_GET['token']) ? $_GET['token'] : '';

if ($id == '' || $token == '') {
    echo 'Error al generara la peticion';
    exit;
} else {

    $token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);

    if ($token == $token_tmp) {

        $sql = $con->prepare("SELECT count(id) FROM modulos WHERE id=? AND estado=1");
        $sql->execute([$id]);
        if ($sql->fetchColumn() > 0) {

            $sql = $con->prepare("SELECT nombre, descripcion FROM modulos WHERE id=? AND estado=1
            LIMIT 1");
            $sql->execute([$id]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            $nombre = $row['nombre'];
            $descripcion = $row['descripcion'];
            $dir_images = '../img/modulos/' . $id . '/';

            $rutaimg = $dir_images . 'principal.jpg';

            if (!file_exists($rutaimg)) {
                $rutaimg = '../img/no-photo.jpg';
            }

            $imagenes = array();
            if (file_exists($dir_images)) {
                $dir = dir($dir_images);

                while (($archivo = $dir->read()) != false) {
                    if ($archivo != 'principal.png' && (strpos($archivo, 'png') || strpos($archivo, 'jpg'))) {
                        $imagenes[] = $dir_images . $archivo;;
                    }
                }
                $dir->close();
            }
        }
    } else {
        echo 'Error al generara la peticion';
        exit;
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
    <title>SICOV | Detalles</title>
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
                    <i class='bx bx-menu bx-lg'></i>
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

    <!-- Main -->
    <main>
        <div class="container">
            <div class="row">
                <div class="col-md-6 order-md-1">

                    <div id="carouselImages" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="<?php echo $rutaimg ?>" class="d-block w-100">
                            </div>
                            <?php foreach ($imagenes as $img) { ?>
                                <div class="carousel-item">

                                    <img src="<?php echo $img ?>" class="d-block w-100">

                                </div>
                            <?php } ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#arouselImages" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselImages" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
                <div class="col-md-6 order-md-2">
                    <h2><?php echo $nombre; ?></h2>
                    <p class="lead">
                        <?php echo $descripcion; ?>
                    </p>

                    <div class="d-grid gap-3 col-10 mx-auto">
                        <button id="addCarrito" data-bs-toggle="modal" data-bs-target="#moadlaAddCarrito" class="btn btn-main" type="button" onclick="addProducto(<?php echo $id; ?>, '<?php echo $token_tmp; ?>')">Agregar al carrito</button>
                    </div>

                </div>
            </div>
        </div>
    </main>


    <!-- Modal -->
    <div class="modal fade" id="moadlaAddCarrito" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Modulo añadido al carrito</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img style="max-height: 100px; width: auto; " src="data:image/png;base64,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"/>
                    <h3>Modulo añadido al carrito</h3>
                </div>
                <div class="modal-footer">
                    <a href="modulos.php" class="btn btn-primary btn-sm" >Ver mas modulos</a>
                    <a href="checkout.php"  class="btn btn-success btn-sm">Ver carrito</a>
                </div>
            </div>
        </div>
    </div>


    <!--Bootstrap-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="js/app.js"></script>
    <script>
        function addProducto(id, token) {
            let url = '../clases/carrito.php'
            let boton = document.getElementById("addCarrito");
            let formData = new FormData()
            formData.append('id', id)
            formData.append('token', token)

            fetch(url, {
                    method: 'POST',
                    body: formData,
                    mode: 'cors'
                }).then(response => response.json())
                .then(data => {
                    if (data.ok) {
                        let elemento = document.getElementById("num_cart")
                        elemento.innerHTML = data.numero
                        boton.disabled = true;
                    }
                })
        }
    </script>
</body>

</html>
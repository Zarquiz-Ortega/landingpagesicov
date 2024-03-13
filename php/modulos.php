<?php

require '../config/config.php';
require '../config/database.php';

$db = new Database();
$con = $db->conectar();

$sql = $con->prepare("SELECT id, nombre, desarollo FROM modulos WHERE estado=1");
$sql->execute();
$resuldado = $sql->fetchAll(PDO::FETCH_ASSOC);

//session_destroy(); //vacia el carrito
//print_r($_SESSION); // imprime el arry del carrito

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
<body>

    <!--Header-->
    <header style="background-color: white;">
        <div class="navbar navbar-expand-lg navbar-ligh ">
            <div class="container">
                <a href="index.php" class="navbar-brand col-">
                    <strong><a class="navbar-brand" href="../index.php"><img class="logo" src="../img/logoSicov.png"> &nbsp; <span style=" font-family: 'nasalization', sans-serif;">SICOV</span></a></strong>
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
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                <?php foreach ($resuldado as $row) {?>
                    <div class="col">
                        <div class="card shadow-sm ">
                            <?php
                            $id = $row['id'];
                            $imagen = "../img/modulos/" . $id . "/principal.jpg";

                            if (!file_exists($imagen)) {
                                $imagen = "../img/no-photo.jpg";
                            }
                            ?>
                            <img src="<?php echo $imagen; ?> ">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <?php echo $row['nombre']; ?>
                                </h5>
                                <a href="details.php?id=<?php echo $row['id']; ?>&token=<?php echo hash_hmac('sha1', $row['id'], KEY_TOKEN); ?>"  class="btn btn-main <?php if(isset($_SESSION['carrito']['productos'][$id]) > 0){?> disabled <?php } ?>">Detalles</a>
                                
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </main>

    <!--Bootstrap-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="js/app.js"></script>
    <script>
        function addProducto(id, token) {
            let url = 'clases/carrito.php'
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
                    }
                })
        }
    </script>
</body>

</html>
<?php
require '../config/config.php';
require '../config/database.php';

$db = new Database();
$con = $db->conectar();

$productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;
//print_r($_SESSION);  //imprime el array del carrito
$lista_carrito = array();

if ($productos != null) {

    foreach ($productos as $clave => $cantidad) {
        $sql = $con->prepare("SELECT id, nombre, $cantidad AS cantidad FROM modulos WHERE 
        id=? AND estado=1");
        $sql->execute([$clave]);
        $lista_carrito[] = $sql->fetch(PDO::FETCH_ASSOC);
    }
}
//session_destroy(); //vacia el carrito

?>

<!DOCTYPE html>
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
    <title>SICOV | Checkout</title>
    <style>
        main > .container {
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
                        <i class='bx bx-cart bx-sm'></i><span id="num_cart" class="badge bg-dark"><?php echo $num_cart;?></span>
                    </a>
                </div>
            </div>
        </div>

    <main>
        <div class="container">
            <div class="table-responsive">
                <table class="table">
                    <thead class="table-dark">
                        <tr>
                            <th><p class="h5">Modulos</p></th>
                            <th colspan="3"></th>
                            <th colspan="2"></th>
                            <th colspan="1"></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($lista_carrito == null) {
                            echo '<tr><td colspan=8 class="text-center"><b>Lista vacia</b></td></tr>';
                        } else {

                            $total = 0;
                            foreach ($lista_carrito as $producto) {
                                $_id = $producto['id'];
                                $nombre = $producto['nombre'];
                        ?>
                                <tr>
                                    <td><p class="h5"><?php echo $nombre; ?></p></td>
                                    <td colspan="3"></td>
                                    <td colspan="2"></td>
                                    <td colspan="1"></td>

                                    <td>
                                        <a id="eliminar" class="btn btn-warning btn-sm" data-bs-id="<?php echo $_id; ?>" data-bs-toggle="modal" data-bs-target="#eliminaModal"><i class='bx bxs-trash bx-sm' style='color:#00000' ></i></a>
                                    </td>
                                </tr>
                            <?php  } ?>

                    </tbody>

                </table>
            </div>
            <div class="row">
                <div class="col-md-5 offset-md-7 d-grid grap-2">
                    <a href="cotizacion.php" class="btn btn-outline-success btn-lg ">Solicitar Cotización</a>
                </div>
            <?php } ?>
            </div>
        </div>
    </main>

    <!-- Modal -->
    <div class="modal fade" id="eliminaModal" tabindex="-1" aria-labelledby="eliminaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="eliminaModalLabel">Alerta</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Desea eliminar el prodoucto de la lista?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button id="btn-elimina" type="button" class="btn btn-danger" onclick="eliminar()">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
    <script>
        let eliminaModal = document.getElementById('eliminaModal')
        eliminaModal.addEventListener('show.bs.modal', function(event) {
            let button = event.relatedTarget
            let id = button.getAttribute('data-bs-id')
            let buttonElimina = eliminaModal.querySelector('.modal-footer #btn-elimina')
            buttonElimina.value = id
        })

        function eliminar() {

            let buttonElimina = document.getElementById('btn-elimina')
            let id = buttonElimina.value

            let url = '../clases/actualizar_carrito.php'
            let formData = new FormData()
            formData.append('action', 'eliminar')
            formData.append('id', id)

            fetch(url, {
                    method: 'POST',
                    body: formData,
                    mode: 'cors'
                }).then(response => response.json())
                .then(data => {
                    if (data.ok) {
                        location.reload()
                    }
                })
        }
    </script>
</body>

</html>
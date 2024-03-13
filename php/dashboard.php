<?php

require '../config/config.php';
require '../config/database.php';

$db = new Database();
$con = $db->conectar();

if(isset($_SESSION['user']['user_name'])){

}else{
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
    <title>SICOV | dashboard</title>
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
        <div class="container py-4  text-center">
            <div class="row py-4">
                <div class="col">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr class="table-dark">
                                    <th colspan="4">
                                        <div class="row  align-items-center">
                                            <div class="col-auto">
                                                <label class="form-label" for="num_reguistros">Mostrar:</label>
                                            </div>
                                            <div class="col-auto">
                                                <select class="form-select w-100" name="num_reguistros" id="num_reguistros">
                                                    <option value="5">5</option>
                                                    <option value="10">10</option>
                                                    <option value="15">25</option>
                                                </select>
                                            </div>
                                            <div class="col-auto">
                                                <label class="form-label" for="num_reguistros">reguistros</label>
                                            </div>
                                        </div>
                                    </th>
                                    <th></th>
                                    <th><label class="col-form-label" for="campo">Buscar:</label></th>
                                    <th><input type="text" class="form-control" id="campo" name="campo"></th>
                                </tr>
                                <tr>
                                    <th class="sort asc">#</th>
                                    <th class="sort asc">Nombre Completo </th>
                                    <th class="sort asc">Asunto</th>
                                    <th class="sort asc">Empresa</th>
                                    <th class="sort asc">Estatus</th>
                                    <th class="sort asc">fecha</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="content">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <label id="lbl-total"></label>
                </div>
                <div class="col-6" id="nav-paguinacion"></div>
                <input type="hidden" id="paguina" value="1">
                <input type="hidden" id="orderCol" value="0">
                <input type="hidden" id="orderType" value="asc">
            </div>
        </div>
    </main>


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="index.php" method="post" autocomplete="off" class="row g-4">
                        <div class="form-group col-md-6">
                            <input type="text" class="form-control rounded-3 border border-success" placeholder="Nombre Completo" id="nombres" name="nombres">
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" class="form-control rounded-3 border border-success" placeholder="Telefono" id="telefono" name="telefono">
                        </div>
                        <div class="form-group col-md-12">
                            <input type="email" class="form-control rounded-3 border border-success" placeholder="Correo Electronico: correro@ejemplo.com" id="email" name="email">
                        </div>
                        <div class="form-group col-md-12">
                            <textarea id="mensaje" name="mensaje" cols="30" rows="4" class="form-control rounded-3 border border-success" placeholder="Cuentanos tu duda..."></textarea>
                        </div>
                        <div class="text-center">
                            <button class="btn btn-main" type="submit">Enviar</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        getData()

        document.getElementById("campo").addEventListener("keyup", function() {
            getData(1)
        }, false)
        document.getElementById("num_reguistros").addEventListener("change", function() {
            getData()
        }, false)


        //peticion de AJAX
        function getData() {
            let input = document.getElementById("campo").value
            let num_reguistros = document.getElementById("num_reguistros").value
            let content = document.getElementById("content")
            let paguina = document.getElementById("paguina").value
            let orderCol = document.getElementById("orderCol").value
            let orderType = document.getElementById("orderType").value


            if (paguina == null) {
                paguina = 1
            }

            let url = "../clases/load.php"
            let formaData = new FormData()
            formaData.append('campo', input)
            formaData.append('reguistros', num_reguistros)
            formaData.append('paguina', paguina)
            formaData.append('orderCol', orderCol)
            formaData.append('orderType', orderType)

            fetch(url, {
                method: "POST",
                body: formaData
            }).then(response => response.json()).then(data => {
                content.innerHTML = data.data
                document.getElementById("lbl-total").innerHTML = 'Mostrando ' + data.totalFiltro + ' de ' + data.totalReguistros + ' reguistros'
                document.getElementById("nav-paguinacion").innerHTML = data.paguinacion
            }).catch(err => console.log(err))

        }

        function nextPage(paguina) {
            document.getElementById('paguina').value = paguina
            getData()
        }

        let colums = document.getElementsByClassName("sort")
        let tamaño = colums.length
        for (let i = 0; i < tamaño; i++) {
            colums[i].addEventListener("click", ordenar)
        }

        function ordenar(e) {

            let elemento = e.target

            document.getElementById('orderCol').value = elemento.cellIndex

            if (elemento.classList.contains("asc")) {
                document.getElementById("orderType").value = "asc"
                elemento.classList.remove("asc")
                elemento.classList.add("desc")
            } else {
                document.getElementById('orderType').value = "desc"
                elemento.classList.remove("desc")
                elemento.classList.add("asc")
            }

            getData()
        }
    </script>

    <!--Bootstrap-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <script src="../js/app.js"></script>



</body>

</html>
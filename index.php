<?php

require_once 'config/config.php';
require_once 'config/database.php';
require 'clases/userFunciones.php';

$db = new Database();
$con = $db->conectar();

$errors = [];
$mensajes = [];

$proceso = isset($_GET['cotizacion']) ? 'cotizacion'  : null;
if ($proceso == 'cotizacion') {
    $mensajes[] = "Se a enviado su cotización en breves lo contactará un ejecutivo";
} elseif (isset($_GET['mensaje']) ? 'mensaje'  : null) {
    $mensajes[] = "Se a enviado su mensaje lo contactará un ejecutivo";
}

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


    if (esNulo([$nombres, $apellidos, $telefono, $nom_empresa, $email, $mensaje, $modulos, $asunto, $estatus])) {
        $errors[] = "Debe llenar todos los campos.";
    } else {
        if (!esEmail($email)) {
            $errors[] = "La dirección de correo $email no es valida.";
        }
        if (esTelefono($telefono)) {
            $errors[] = "El numero de telefono ( $telefono ) no es valido.";
        } else {
            if (!esNacional($telefono))
                $errors[] = "El numero de telefono ( $telefono ) debe empezar con 56 0 55.";
        }
        if (count($errors) == 0) {
            $id = registraCustomers([$nombres, $apellidos, $telefono, $nom_empresa, $email], $con);
            if ($id > 0) {

                if (reguistroMensaje([$mensaje, $modulos, $asunto, $fecha, $id, 1], $con)) {
                    session_destroy();
                    header("location: index.php?mensaje");
                } else {
                    $errors[] = "ocurrio un error al generara el reguistro";
                }
            } else {
                $errors[] = "ocurrio un error al generara el reguistro";
            }
        }
    }
}


?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--Favicon-->
    <link rel="shortcut icon" href="img/sicovLogo.ico">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="css/line-icons.css">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.cdnfonts.com/css/nasalization" rel="stylesheet">
    <!--Icon-Font-->
    <script src="https://kit.fontawesome.com/eb496ab1a0.js" crossorigin="anonymous"></script>
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <title>SICOV | Inicio</title>
</head>

<body data-bs-spy="scroll" data-bs-target=".navbar">


    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">¿Tengo una duda?...</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="index.php" method="post" autocomplete="off" class="row g-4">

                        <input type="hidden" name="modulos" id="modluos" value="sin datos">
                        <input type="hidden" name="asunto" id="asunto" value="Pregunta">
                        <input type="hidden" name="estatus" id="modluos" value="1">
                        <input type="hidden" name="accion" id="accion" value="pregunta">

                        <div class="form-group col-md-6">
                            <input type="text" class="form-control rounded-3 border border-success" placeholder="Nombre" id="nombres" name="nombres">
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" class="form-control rounded-3 border border-success" placeholder="Apellido" id="apellidos" name="apellidos">
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" class="form-control rounded-3 border border-success" placeholder="Telefono" id="telefono" name="telefono">
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" class="form-control rounded-3 border border-success" placeholder="Nombre de la empresa" id="nom_espresa" name="nom_empresa">
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
            </div>
        </div>
    </div>

    <!--Whatsapp-->
    <a href="https://api.whatsapp.com/send?phone=525576569581&text=Hola, me gustaría obtener más información sobre sus servicios." class="btn-wsp" target="_blank">
        <i class="fa fa-whatsapp icono"></i>
    </a>

    <!--Boton del modal-->
    <div class="btn-from"><button type="button" class="btn-modal" data-bs-toggle="modal" data-bs-target="#staticBackdrop"><i class='bx bxs-help-circle bx-md bx-tada'></i>¿tienes alguna
            duda?...</button>
    </div>

    <!--navbar-->
    <nav class="navbar fixed-top navbar-expand-lg navbar-light bg-white">
        <div class="container">
            <a class="navbar-brand" href="index.php"><img class="logo" src="img/logoSicov.png">&nbsp;<span style=" font-family: 'nasalization', sans-serif;">SICOV</span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#Inicio">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#sobreNosostros">Acerca de</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#Modulos">Modulos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#Beneficios">Beneficios</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#Mejoras">Mejoras posibles</a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="#reseñas">reseñas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#Noticias">Noticias</a>-->
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#Contacto">Contacto</a>
                    </li>
                </ul>
                &nbsp;<a class="btn btn-main" href="php/modulos.php">Cotizar</a>
            </div>
        </div>
    </nav>

    <!--hero-->
    <section id="Inicio" class="bg-cover hero-section" style="background-image: url(img/cover_1.jpg);">
        <div class="overlay"></div>
        <div class="container text-white text-center">
            <div class="row">
                <div class="col-12">
                    <?php mostrarMensajes($mensajes); ?>
                    <?php mostrarErrors($errors); ?>
                    <h1 class="display-1"><span style="font-family: 'nasalization', sans-serif;">SICOV</span><br></h1>
                    <p class="h3">¡EXPLORA LA EVOLUCION!<br></p>
                    <p>¿Buscas una transformación revolucionaria en tu empresa?<br> ¡Has llegado al lugar correcto! Sabemos que la transformación es la clave,<br> y <span style=" font-family: 'nasalization', sans-serif;">SICOV</span> es la herramienta que hará que eso suceda.</p>
                    <a href="php/modulos.php" class="btn btn-main">Cotiza ahora</a>
                </div>
            </div>
        </div>
    </section>

    <!--sobreNosostros-->
    <section id="sobreNosostros" class="text-center">
        <div class="container">
            <div class="row">
                <div class="col-12 section-intro text-center">
                    <h1>Sobre nosostros</h1>
                    <div class="divider"></div>
                    <p>En este apartado aprece to la informacion sobre nosotros</p>
                </div>
            </div>
            <div class="card border-light mb-3">
                <div class="row g-0">
                    <div class="col-md-4 align-items-end">
                        <img src="img/company.svg" class="img-fluid rounded">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <div class="card-title">
                                <h3>Somos <span style=" font-family: 'nasalization', sans-serif;">SICOV</span></h3>
                            </div>
                            <p class="card-text">
                                En <span style=" font-family: 'nasalization', sans-serif;">SICOV</span>, no solo ofrecemos soluciones, sino que impulsamos el crecimiento de las pequeñas y medianas empresas con nuestro potente CRM. Nos definimos por proporcionar herramientas flexibles y adaptativas que se sincronizan perfectamente con tus objetivos empresariales.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card border-light mb-3">
                <div class="row g-0">
                    <div class="col-md-8">
                        <div class="card-body">
                            <div class="card-title">
                                <h3>Visión en Acción</h3>
                            </div>
                            <p class="card-text">
                                Visualizamos un horizonte donde cada pyme prospera con la ayuda de tecnologías accesibles y eficientes, lideradas por nuestro CRM. En <span style=" font-family: 'nasalization', sans-serif;">SICOV</span>, somos el catalizador que convierte tus metas en realidades, superando tus expectativas con soluciones innovadoras.</p>
                        </div>
                    </div>
                    <div class="col-md-4 align-items-end">
                        <img src="img/visionary.svg" class="img-fluid rounded">
                    </div>
                </div>
            </div>
            <div class="card border-light mb-3">
                <div class="row g-0">
                    <div class="col-md-4 align-items-end">
                        <img src="img/mision.svg" class="img-fluid rounded">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <div class="card-title">
                                <h3>Misión Empresarial Dinámica</h3>
                            </div>
                            <p class="card-text">
                                Nuestra misión en constante evolución es proporcionar a las pymes las herramientas necesarias para su crecimiento, destacando la flexibilidad y la asequibilidad de nuestro CRM. En <span style=" font-family: 'nasalization', sans-serif;">SICOV</span>, creemos en la adaptabilidad, moldeando nuestra plataforma para satisfacer tus necesidades cambiantes.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card border-light mb-3">
                <div class="row g-0">
                    <div class="col-md-8">
                        <div class="card-body">
                            <div class="card-title">
                                <h3>Adaptabilidad en el Centro</h3>
                            </div>
                            <p class="card-text">
                                Especializándonos en hacer que la tecnología CRM se adapte a ti, no al revés, en S<span style=" font-family: 'nasalization', sans-serif;">SICOV</span>ICOV entendemos que cada empresa es única. Aquí, la adaptabilidad no es una opción; es nuestro compromiso, asegurando que tu empresa pueda aprovechar al máximo nuestras soluciones.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4 align-items-end">
                        <img src="img/ada.svg" class="img-fluid rounded">
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!--cambio-->
    <section id="cambio" class="bg-cover" style="background-image: url(img/cover_2.jpg);">
        <div class="overlay"></div>
        <div class="container text-white text-center">
            <div class="row">
                <div class="col-12 section-intro text-center">
                    <h1>¿Por qué <span style=" font-family: 'nasalization', sans-serif;">SICOV</span> es un juego de cambio?</h1>
                    <p></p>
                    <div class="divider"></div>
                </div>
            </div>
            <div class="row gx-4 gy-5">
                <div class="col-md-4 feature">
                    <div class="icon"><i class='bx bxs-hot'></i></div><br>
                    <p>Incrementa tus ingresos: Identifica oportunidades ocultas y supera tus objetivos de ventas.</p>
                </div>
                <div class="col-md-4 feature">
                    <div class="icon"><i class='bx bxs-rocket'></i></div><br>
                    <p>Despídete de lo Manual: Automatiza tareas para que puedas concentrarte en lo estratégico.</p>
                </div>
                <div class="col-md-4 feature">
                    <div class="icon"><i class='bx bx-target-lock'></i></div><br>
                    <p>Conoce a tus clientes: Obtén información profunda para ofrecer experiencias personalizadas.</p>
                </div>
                <div class="col-md-3 feature">
                    <div class="icon"><i class='bx bx-cloud-upload'></i></div><br>
                    <p><b>Lleva tu Empresa a la Nube:</b><br>Accede a tus datos en cualquier momento y lugar.<br><b><span style=" font-family: 'nasalization', sans-serif;">SICOV</span>: Tu viaje hacia el éxito asegurado</b></p>
                </div>
                <div class="col-md-3 feature">
                    <div class="icon"><i class='bx bx-briefcase'></i></div><br>
                    <p><b>Expertos en <span style=" font-family: 'nasalization', sans-serif;">SICOV</span></b><br>Nuestro equipo certificado sabe cómo convertir <span style=" font-family: 'nasalization', sans-serif;">SICOV</span> en tu mayor activo</p>
                </div>
                <div class="col-md-3 feature">
                    <div class="icon"><i class='bx bx-list-check'></i></div><br>
                    <p><b>Resultados garantizados</b><br>Tu éxito es nuestra misión.</p>
                </div>
                <div class="col-md-3 feature">
                    <div class="icon"><i class='bx bx-group'></i></div><br>
                    <p><b>Más que una Implementación</b><br>No nos detenemos. Te acompañamos a medida que crece y evolucionas</p>
                </div>
            </div>
        </div>
    </section>

    <!--Modulos-->
    <section id="Modulos" class="text-center">
        <div class="container">
            <div class="row">
                <div class="col-12 section-intro text-center">
                    <h1>Modulos principales de <span style=" font-family: 'nasalization', sans-serif;">SICOV</span></h1>
                    <p>Estos son los modulos principales de <span style=" font-family: 'nasalization', sans-serif;">SICOV</span></p>
                    <div class="divider"></div>
                </div>
            </div>
            <div class="row gx-4 gy-5">
                <div class="col-md-4 feature">
                    <div class="icon"><i class='bx bxs-user-account'></i></div>
                    <h5 class="mt-4 mb-3">Login</h5>
                    <p>Es cómo los usuarios acceden al <span style=" font-family: 'nasalization', sans-serif;">SICOV</span> con su usuario y contraseña para usar sus funciones y datos.</p>
                </div>
                <div class="col-md-4 feature">
                    <div class="icon"><i class="icon-browser"></i></div>
                    <h5 class="mt-4 mb-3">Inventario</h5>
                    <p>Mantiene un seguimiento preciso de los activos disponibles para la gestión eficiente y decisiones informadas.</p>
                </div>
                <div class="col-md-4 feature">
                    <div class="icon"><i class='bx bx-user-plus'></i></div>
                    <h5 class="mt-4 mb-3">Registro de clientes/proveedores</h5>
                    <p>Guarda datos importantes de las empresas con las que se hace negocios, facilitando su manejo y seguimiento.</p>
                </div>
                <div class="col-md-4 feature">
                    <div class="icon"><i class="icon-calendar"></i></div>
                    <h5 class="mt-4 mb-3">Agenda</h5>
                    <p>Organiza eventos y tareas eficientemente. teniendo un calendario para verlo graficamente.</p>
                </div>
                <div class="col-md-4 feature">
                    <div class="icon"><i class='bx bx-search'></i></div>
                    <h5 class="mt-4 mb-3">Buscar cuentas/seguimiento de venta</h5>
                    <p>Rastreaccordion-accordion y acceder rápidamente a la información del cliente y ventas, facilitando la gestión y el análisis comercial.</p>
                </div>
                <div class="col-md-4 feature">
                    <div class="icon"><i class='bx bxs-layout'></i></div>
                    <h5 class="mt-4 mb-3">Dashboard</h5>
                    <p>Muestra de manera visual y concisa datos importantes en tiempo real ayudando a tomar decisiones y monitorear el rendimiento.</p>
                </div>
            </div>
        </div>
    </section>

    <!--Beneficios-->
    <section id="Beneficios" class="bg-cover" style="background-image: url(img/cover_2.jpg);">
        <div class="overlay"></div>
        <div class="container text-white text-center">
            <div class="row">
                <div class="col-12 section-intro">
                    <h1>Beneficios con <span style=" font-family: 'nasalization', sans-serif;">SICOV</span></h1>
                    <p>Al tener <span style=" font-family: 'nasalization', sans-serif;">SICOV</span> optienes acceso a estos beneficio.</p>
                </div>
            </div>
            <div class="row gy-4">
                <div class="col-4 feature">
                    <div class="icon"><i class='bx bxs-data bx-lg'></i></div>
                    <h3 class="mt-3 mb-2">Control de datos.</h3>
                </div>
                <div class="col-4 feature">
                    <i class='bx bx-microchip bx-lg'></i>
                    <h3 class="mt-3 mb-2">Procesos fluidos.</h3>
                </div>
                <div class="col-4 feature">
                    <i class='bx bxs-brain bx-lg'></i>
                    <h3 class="mt-3 mb-2">Toma de decisiones estratégica.</h3>
                </div>
                <div class="col-4 feature">
                    <i class='bx bxs-user-check bx-lg'></i>
                    <h3 class="mt-3 mb-2">Segimiento a clientes.</h3>
                </div>
                <div class="col-4 feature">
                    <i class='bx bx-broadcast bx-lg'></i>
                    <h3 class="mt-3 mb-2">Telemetria.</h3>
                </div>
                <div class="col-4 feature">
                    <i class='bx bxs-cart-add bx-lg'></i>
                    <h3 class="mt-3 mb-2">Maximiza ventas.</h3>
                </div>
            </div>
        </div>
    </section>

    <!--Mejoras posibles-->
    <section id="Mejoras" class="text-center">
        <div class="container">
            <div class="row">
                <div class="col-12 section-intro text-center">
                    <h1>Mejoras posibles</h1>
                    <div class="divider"></div>
                    <p>Estan serial los modulos futuros para <span style=" font-family: 'nasalization', sans-serif;">SICOV</span></p>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="service">
                        <div class="service-img">
                            <div class="shadow-lg"><img src="img/imgen_1.jpg"></div>
                            <div class="icon"><i class='bx bx-category bx-lg'></i></div>
                        </div>
                        <h5 class="mt-5 pt-4">Fusionando talento</h5>
                        <p>Maneja la información y procesos de los empleados, incluyendo nómina, horarios, permisos.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="service">
                        <div class="service-img">
                            <div class="shadow-lg"><img src="img/imagen_2.jpg" alt=""></div>
                            <div class="icon"><i class='bx bx-purchase-tag-alt bx-lg'></i></div>
                        </div>
                        <h5 class="mt-5 pt-4">Ticket service</h5>
                        <p>Facilita la comunicación y resolucion de problemas entre diferentes áreas de la organización.
                        </p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="service">
                        <div class="service-img">
                            <div class="shadow-lg"><img src="img/imagen_3.jpg"></div>
                            <div class="icon"><i class='bx bx-planet bx-lg'></i></i></div>
                        </div>
                        <h5 class="mt-5 pt-4">Universo de conexiones</h5>
                        <p><span style=" font-family: 'nasalization', sans-serif;">SICOV</span> es altamente adaptable a sus necesidades, permitiéndonos implementar las herramientas
                            específicas que busca o requiere para su funcionamiento óptimo.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="service">
                        <div class="service-img">
                            <div class="shadow-lg"><img src="img/imgen_4.jpg"></div>
                            <div class="icon"><i class='bx bx-code-alt bx-lg'></i></div>
                        </div>
                        <h5 class="mt-5 pt-4">Integracion de IA</h5>
                        <p>La inteligencia artificial brindaria recomendaciones ayudando a la toma de decisiones estrategica.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--reseñas
    <section id="reseñas" class="text-center">
        <div class="container">
            <div class="row">
                <div class="col-12 section-intro text-center">
                    <h1>Nuestros clientes</h1>
                    <div class="divider"></div>
                    <p>...</p>
                </div>
            </div>
            <div class="row g-4 text-start">
                <div class="col-md-4">
                    <div class="review p-4">
                        <div class="person">
                            <img src="img/user.png" alt="">
                            <div class="text ms-3">
                                <h6 class="mb-0">Nombre</h6>
                                <small>Google</small>
                            </div>
                        </div>
                        <p class="pt-4">...</p>
                        <div class="stars">
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="review p-4">
                        <div class="person">
                            <img src="img/user.png" alt="">
                            <div class="text ms-3">
                                <h6 class="mb-0">Nombre</h6>
                                <small>Google</small>
                            </div>
                        </div>
                        <p class="pt-4">...</p>
                        <div class="stars">
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="review p-4">
                        <div class="person">
                            <img src="img/user.png" alt="">
                            <div class="text ms-3">
                                <h6 class="mb-0">Nombre</h6>
                                <small>Google</small>
                            </div>
                        </div>
                        <p class="pt-4">...</p>
                        <div class="stars">
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>-->

    <!--Noticias
    <section id="Noticias" class="bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12 section-intro text-center"  >
                    <h1>Noticias</h1>
                    <div class="divider"></div>
                    <p>...</p>
                </div>
            </div>
            <div class="row text-start">
                <div class="col-md-4"  >
                    <img src="img/no-photo.jpg" alt="">
                    <div class="mt-4">
                        <small>publicado en <a href="#">...</a>, xx mes 20xx</small>
                        <h5 class="mt-1 mb-2"><a href="#">Titulo del post</a></h5>
                        <p>...</p>
                    </div>
                </div>
                <div class="col-md-4"  >
                    <img src="img/no-photo.jpg" alt="">
                    <div class="mt-4">
                        <small>publicado en <a href="#">...</a>, xx mes 20xx</small>
                        <h5 class="mt-1 mb-2"><a href="#">Titulo del post</a></h5>
                        <p>...</p>
                    </div>
                </div>
                <div class="col-md-4"  >
                    <img src="img/no-photo.jpg" alt="">
                    <div class="mt-4">
                        <small>publicado en <a href="#">...</a>, xx mes 20xx</small>
                        <h5 class="mt-1 mb-2"><a href="#">Titulo del post</a></h5>
                        <p>...</p>
                    </div>
                </div>
            </div>
        </div>
    </section>-->

    <!--contacto-->
    <section id="Contacto" class="bg-cover text-white" style="background-image: url(img/cover_2.jpg);">
        <div class="overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-12 section-intro text-center">
                    <h1>Contacto</h1>
                    <div class="divider"></div>
                    <p>¿Tienes alguna duda?... <br>
                        dejanos tus datos y nosotros nos ponemos en contacto contigo.
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <form action="index.php" method="post" autocomplete="off" class="row g-4">

                        <input type="hidden" name="modulos" id="modluos" value="sin datos">
                        <input type="hidden" name="asunto" id="asunto" value="Pregunta">
                        <input type="hidden" name="estatus" id="modluos" value="1">
                        <input type="hidden" name="accion" id="accion" value="pregunta">

                        <div class="form-group col-md-6">
                            <input type="text" class="form-control rounded-3" placeholder="Nombre" id="nombres" name="nombres">
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" class="form-control rounded-3" placeholder="Apellido" id="apellidos" name="apellidos">
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" class="form-control rounded-3" placeholder="Telefono" id="telefono" name="telefono">
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" class="form-control rounded-3" placeholder="Nombre de la empresa" id="nom_espresa" name="nom_empresa">
                        </div>
                        <div class="form-group col-md-12">
                            <input type="email" class="form-control rounded-3" placeholder="Correo Electronico: correro@ejemplo.com" id="email" name="email">
                        </div>
                        <div class="form-group col-md-12">
                            <textarea id="mensaje" name="mensaje" cols="30" rows="4" class="form-control rounded-3" placeholder="Cuentanos tu duda..."></textarea>
                        </div>
                        <div class="text-center">
                            <button class="btn btn-main" type="submit">Enviar</button>
                        </div>
                    </form>
                </div>
                <div class="col-4 text-start">
                    <div class="h2"><a href="https://www.facebook.com/people/SICOV/61552185204730/?mibextid=ZbWKwL" class="link-dark"><span style="color: cornflowerblue;"><i class='bx bxl-facebook-circle'></i> Sicov</span></a></div>
                    <div class="h2"><a href="https://www.instagram.com/sicov_crm/?igshid=OGQ5ZDc2ODk2ZA%3D%3D" class="link-dark"><span style="color: palevioletred ;"><i class='bx bxl-instagram-alt'></i> sicov_crm</span></a></div>
                    <div class="h2"><a href="https://api.whatsapp.com/send?phone=525576569581&text=Hola, me gustaría obtener más información sobre sus servicios." class="link-dark"><span style="color: greenyellow ;"><i class='bx bxl-whatsapp'></i> 525576569581</span></a></div>
                    <div class="h3"><a href="https://www.google.com/maps/place/19%C2%B040'29.6%22N+99%C2%B001'34.3%22W/@19.674898,-99.0287698,17z/data=!3m1!4b1!4m4!3m3!8m2!3d19.674898!4d-99.0261949?entry=ttu" class="link-dark"><span style="color: aquamarine ;"><i class='bx bxs-buildings'></i> Calle capilla Capilla, 55770 Ojo de Agua, Méx.</span></a></div>
                </div>
            </div>
        </div>
    </section>

    <!--footer-->
    <footer class="py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0">Copyright © 2023 SICOV.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div>
                        <a href="#"><i class='bx bxl-facebook-circle'></i></a>
                        <a href="#"><i class='bx bxl-twitter'></i></a>
                        <a href="#"><i class='bx bxl-instagram'></i></a>
                        <a href="#"><i class='bx bxl-whatsapp'></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!--Bootstrap-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="js/app.js"></script>
</body>

</html>
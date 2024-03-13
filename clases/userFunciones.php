<?php

function esNulo(array $parametros)
{
    foreach ($parametros as $parametro) {
        if (strlen(trim($parametro)) < 1) {
            return true;
        }
    }
    return false;
}

function esEmail($email)
{
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    }
    return false;
}

function esTelefono($telefono)
{
    if (preg_match("/^([0-9](10))$/", $telefono)) {
        return true;
    }
    return false;
}

function esNacional($telefono)
{
    if (preg_match("/^(55|56)[0-9]{8}$/", $telefono)) {
        return true;
    }
    return false;
}

function obtenerFecha()
{
    $fecha_actual = date("Y-m-d H:i:s");
    return $fecha_actual;
}

function mostrarErrors(array $erros)
{
    if (count($erros) > 0) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert"><dl>';
        foreach ($erros as $error) {
            echo '<dd>' . $error . '</dd>';
        }
        echo '</dl>';
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
        echo '</div>';
    }
}
function mostrarMensajes(array $mensajes)
{
    if (count($mensajes) > 0) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert"><dl>';
        foreach ($mensajes as $mensajes) {
            echo '<dd>' . $mensajes . '</dd>';
        }
        echo '</dl>';
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
        echo '</div>';
    }
}

function registraCustomers(array $datos, $con)
{
    $sql = $con->prepare("INSERT INTO customers (nombres, apellidos, telefono, nom_empresa, email) VALUES (?,?,?,?,?)");

    if ($sql->execute($datos)) {

        return $con->lastInsertId();
    }
}
return 0;

function reguistroMensaje(array $datos, $con)
{
    $sql = $con->prepare("INSERT INTO mensajes (mensaje, modulos, asunto, fecha, id_customers, id_estado) VALUES (?,?,?,?,?,?)");

    if ($sql->execute($datos)) {

        return $con->lastInsertId();
    }
}


function login($usuario, $password, $con)
{
    $sql = $con->prepare("SELECT  nombres, apellidos, usuario, password FROM usuarios WHERE usuario LIKE ? LIMIT 1");
    $sql->execute([$usuario]);
    if ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
        if ($password == $row['password']) {
            $_SESSION['user']['user_name'] = $row['usuario'];
            $_SESSION['user']['name'] = $row['nombres'];
            $_SESSION['user']['last_name'] = $row['apellidos'];
            header("location: ../Dashboard/index.php");
        }
    }
    return 'El usuario y/o contraseña son incorrectos';
}

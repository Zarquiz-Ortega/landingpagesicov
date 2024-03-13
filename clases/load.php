<?php
/*
Script: caraga de datos de lado del servidor con PHP y MySQL
autor: Zarquiz ortega 
fecha: 11-09-2023
*/

//conexion a la base de datos
$con = new mysqli("127.0.0.1", "root", "", "sicov");


$columns  = ['id', 'nombres', 'apellidos', 'nom_empresa', 'asunto', 'estado', 'fecha'];

$table = "customers";

$id = 'id';
//validamos la variable campo para el buscador 
$campo = isset($_POST['campo']) ? $con->real_escape_string($_POST['campo']) : null;

//creacion del wher para la busqueda
$where = '';

if ($campo != null) {
    $where = "WHERE (";

    $count = count($columns);
    for ($i = 0; $i < $count; $i++) {
        $where .= $columns[$i] . " LIKE '%" . $campo . "%' OR ";
    }
    $where = substr_replace($where, "", -3);
    $where .= ")";
}

//limit y paguinacion 

$limit = isset($_POST['reguistros']) ? $con->real_escape_string($_POST['reguistros']) : 5;
$paguina = isset($_POST['paguina']) ? $con->real_escape_string($_POST['paguina']) : 0;

if (!$paguina) {
    $inicio = 0;
    $paguina = 1;
} else {
    $inicio = ($paguina - 1) * $limit;
}

$slimit = "LIMIT $inicio ,  $limit";

//ordenamiento de columnas 

$sOrder = "";
if (isset($_POST['orderCol'])) {
    $orderCol = $_POST['orderCol'];
    $orderType = isset($_POST['orderType']) ? $_POST['orderType'] : 'asc';

    $sOrder = "ORDER BY " . $columns[intval($orderCol)] . ' ' . $orderType;
}



//consulta SQL 
$sql  = "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $columns) . " FROM $table  
$where 
$sOrder
$slimit";
$resultado = $con->query($sql);
$num_row = $resultado->num_rows;

// consulta para total de registros filtrados 
$sqlFiltro = "SELECT FOUND_ROWS()";
$resFiltro = $con->query($sqlFiltro);
$row_filtro = $resFiltro->fetch_array();
$totalFiltro = $row_filtro[0];

// consulta para total de registros filtrados 
$sqlTotal = "SELECT count($id) FROM $table ";
$resTotal = $con->query($sqlTotal);
$row_Total = $resTotal->fetch_array();
$totalReguistros = $row_Total[0];

//Array de areglo de datos
$output = [];
$output['totalReguistros'] = $totalReguistros;
$output['totalFiltro'] = $totalFiltro;
$output['data'] = '';
$output['paguinacion'] = '';

//imprime la consulta para visualisar errores
/*echo $sql;
exit;*/


//asignamos los datos de la tabla al Array
if ($num_row > 0) {

    while ($row = $resultado->fetch_assoc()) {
        $estatus = $row['estado'];
        $output['data'] .= '<tr>';
        $output['data'] .= '<td>' . $row['id'] . ' </td>';
        $output['data'] .= '<td>' . $row['nombres'] . ' ' . $row['apellidos'] . ' </td>';
        $output['data'] .= '<td>' . $row['asunto'] . ' </td>';
        $output['data'] .= '<td>' . $row['nom_empresa'] . ' </td>';
        if ($estatus == 1) {
            $output['data'] .= '<td><div class="bg-primary text-white rounded-3">Nuevo contacto</div></td>';
        } elseif ($estatus == 2) {
            $output['data'] .= '<td><div class="bg-warning text-dark rounded-3">En seguimiento</div></td>';
        } elseif ($estatus == 3) {
            $output['data'] .= '<td><div class="bg-success text-white rounded-3">Venta</div></td>';
        } elseif ($estatus == 4) {
            $output['data'] .= '<td><div class="bg-danger text-white rounded-3">Cancelado</div></td>';
        }
        $output['data'] .= '<td>' . $row['fecha'] . ' </td>';
        $output['data'] .= '<td><a class="btn btn-primary btn-sm" href="edita.php?id=' . $row['id'] . '"><i class="bx bx-notepad bx-sm"></i></a></td>';
        $output['data'] .= '</tr>';
    }
} else {
    $output['data'] .= '<tr>';
    $output['data'] .= '<td colspan="7"> Sin resultados </td>';
    $output['data'] .= '</tr>';
}

if ($output['totalReguistros'] > 0) {
    $totalPaguinas =  ceil($output['totalReguistros'] / $limit);

    $output['paguinacion'] .= '<nav>';
    $output['paguinacion'] .= '<ul class="pagination pagination-sm justify-content-end">';

    //estructura de la paguinacion
    $numeroInicio = 1;
    if (($paguina - 4) > 1) {
        $numeroInicio = $paguina - 4;
    }

    $numeroFin = $numeroInicio + 9;

    if ($numeroFin > $totalPaguinas) {
        $numeroFin = $totalPaguinas;
    }

    for ($i = $numeroInicio; $i <= $numeroFin; $i++) {

        if ($paguina == $i) {
            $output['paguinacion'] .= '<li class = "page-item active">
        <a class="page-link" href="#">' . $i . '</a>
        </li>';
        } else {
            $output['paguinacion'] .= '<li class = "page-item">
        <a class="page-link" href="#" onclick="nextPage(' . $i . ')">' . $i . '</a>
        </li>';
        }
    }

    $output['paguinacion'] .= '</ul>';
    $output['paguinacion'] .= '</nav>';
}

echo json_encode($output, JSON_UNESCAPED_UNICODE);

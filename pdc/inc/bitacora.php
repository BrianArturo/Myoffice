<?php
ini_set('display_errors', 0);
$host = 'localhost';
$user = 'offimovil.com';
$pass = '5m90r#Mt';
$name = 'nube24amarillo';

$mysqli = new mysqli($host, $user, $pass, $name);
if ($mysqli->connect_errno) {
    $sqlerrors[1] = "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

function bitacoraNewCaso($id, $texto)
{
    global $mysqli;
    $modulo = "Adición contacto";
    $fecha = date("y/m/d h:i:s ");

    $SQL_BITACORA = "INSERT INTO bitacora (id_usuario, modulo, fecha, id_caso, descripcion)"
        . "VALUES ('" . $_SESSION["auth_id"] . "','" . $modulo . "',' " . $fecha . "', '" . $id . "', '" . $texto . "')";
    $mysqli->real_query($SQL_BITACORA);

    $mysqli->close();
}

function bitacoraUpdateDesc($id, $texto)
{
    global $mysqli;
    $modulo = "Actualizar Descripción de caso";
    $fecha = date("y/m/d h:i:s ");

    $SQL_BITACORA = "INSERT INTO bitacora (id_usuario, modulo, fecha, id_caso, descripcion)"
        . "VALUES ('" . $_SESSION["auth_id"] . "','" . $modulo . "',' " . $fecha . "', '" . $id . "', '" . $texto . "')";
    $mysqli->real_query($SQL_BITACORA);

    $mysqli->close();
}
function bitacoraUpdateCaso($id, $texto)
{
    global $mysqli;
    $modulo = "Actualizar Información inicial del caso";
    $fecha = date("y/m/d h:i:s ");

    $SQL_BITACORA = "INSERT INTO bitacora (id_usuario, modulo, fecha, id_caso, descripcion)"
        . "VALUES ('" . $_SESSION["auth_id"] . "','" . $modulo . "',' " . $fecha . "', '" . $id . "', '" . $texto . "')";
    $mysqli->real_query($SQL_BITACORA);

    $mysqli->close();
}

function bitacoraContactos($id, $texto)
{
    global $mysqli;
    $modulo = "Creación & Actualización de contactos";
    $fecha = date("y/m/d h:i:s ");

    $SQL_BITACORA = "INSERT INTO bitacora (id_usuario, modulo, fecha, id_caso, descripcion)"
        . "VALUES ('" . $_SESSION["auth_id"] . "','" . $modulo . "',' " . $fecha . "', '" . $id . "', '" . $texto . "')";
    $mysqli->real_query($SQL_BITACORA);

    $mysqli->close();
}

function bitacoraPagos($id, $texto)
{
    global $mysqli;
    $modulo = "Se agrego el pago";
    $fecha = date("y/m/d h:i:s ");

    $SQL_BITACORA = "INSERT INTO bitacora (id_usuario, modulo, fecha, id_caso, descripcion)"
        . "VALUES ('" . $_SESSION["auth_id"] . "','" . $modulo . "',' " . $fecha . "', '" . $id . "', '" . $texto . "')";
    $mysqli->real_query($SQL_BITACORA);

    $mysqli->close();
}

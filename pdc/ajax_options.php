<?php
include("inc/config.php");
session_start();
if (!isset($_SESSION["auth_id"]) ){ header ("Location:index.php"); }
if($_GET['logout']=="logout"){ unset($_SESSION); }

 

$_ID = auyama_decrypt(base64_decode(rawurldecode ($_POST["caso_id"])));
/*
casos_options
    options_id
    caso_id
    name
    value
*/
  
    if( $_POST["function"]=="create_option" ){
        echo "create option ";

        $QUERY_OPTION = "INSERT INTO casos_options ( options_id, caso_id, name, value, option_type)".
                        " values(NULL,'".$_ID."','".$_POST["tags"]."','".$_POST["valor"]."' ,'".$_POST["formato"]."' );  ";
        echo $option_type[$_POST["formato"]];
        $mysqli->real_query($QUERY_OPTION);

    }
    if( $_POST["function"]=="save_pago" ){
        if($_POST["formato"]=='pagoCapital')
        {
            $tipoPago="Pago Capital";
            $conv = array("$" => "", "," => "");
            $cuantia = strtr($_POST["valor"],$conv);
            $UPDATE_QUERY="UPDATE casos SET cuantia=cuantia-".$cuantia." WHERE caso_id=".$_ID;
            $mysqli2->real_query($UPDATE_QUERY);
        }
        else{
            $tipoPago="Otros Pagos";
        }
        $QUERY_OPTION = "INSERT INTO pagos ( pago_id, caso_id,fecha,valor,tipo_pago,descripcion) values (NULL,'".$_ID."','".$_POST["tags"]."','".$_POST["valor"]."','".$tipoPago."','".$_POST["descripcion"]."')";
        //var_dump($QUERY_OPTION);exit();
        $mysqli->real_query($QUERY_OPTION);
    }
    if( $_POST["function"]=="delete_option" ){
        $QUERY_OPTION_DELETE = "DELETE  from casos_options where options_id ='".$_POST["option_id"]."' AND caso_id='".$_ID."'";
        $mysqli->real_query($QUERY_OPTION_DELETE);
        echo  $QUERY_OPTION_DELETE ;
    
    }

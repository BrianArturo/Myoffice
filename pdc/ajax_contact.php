<?php
include("inc/config.php");
session_start();
if (!isset($_SESSION["auth_id"]) ){ header ("Location:index.php"); }
if($_GET['logout']=="logout"){ unset($_SESSION); }

 


if( $_POST["function"]=="update_contact" ){
    $_ID = auyama_decrypt(base64_decode(rawurldecode ($_POST["contact_id"])));
     $query = "UPDATE contacts SET  ".
               "name='".$mysqli->real_escape_string($_POST["name"])."',".
               "phone='".$mysqli->real_escape_string($_POST["phone"])."',".
               "email='".$mysqli->real_escape_string($_POST["email"])."',".
               "nit='".$mysqli->real_escape_string($_POST["nit"])."',".
               "birthplace='".$mysqli->real_escape_string($_POST["birthplace"])."',".
               "birthdate='".$mysqli->real_escape_string($_POST["birthdate"])."',".
               "address='".$mysqli->real_escape_string($_POST["address"])."',".
               "citty= '".$mysqli->real_escape_string($_POST["citty"])."'".
               "WHERE contact_id='".$_ID."'; ";
     //echo $query;
    $mysqli->real_query($query); 
    echo '<i class="fa fa-check-circle" aria-hidden="true"></i> los cambios fueron guardados.';
    sleep(2);
    } 
  
    if( $_POST["function"]=="create_contact" ){


        $query =    "INSERT INTO contacts ".
                    "(contact_id, name, phone, email, nit, birthplace, birthdate, address, citty, status,created_by,datalog) ".
                    "VALUES (".
                    "null, 
                    '".$mysqli->real_escape_string($_POST["name"])."',
                    '".$mysqli->real_escape_string($_POST["phone"])."',
                    '".$mysqli->real_escape_string($_POST["email"])."',
                    '".$mysqli->real_escape_string($_POST["nit"])."',
                    '".$mysqli->real_escape_string($_POST["birthplace"])."',
                    '".$mysqli->real_escape_string($_POST["birthdate"])."',
                    '".$mysqli->real_escape_string($_POST["address"])."',
                    '".$mysqli->real_escape_string($_POST["citty"])."',
                    '1','".$mysqli->real_escape_string($_POST["created_by"])."',
                    CURRENT_TIMESTAMP);";

        $mysqli->real_query($query);
           # print_r($_POST);
            echo '<i class="fa fa-check-circle" aria-hidden="true"></i> los cambios fueron guardados.';
 
    }

?>

<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include("inc/config.php");
session_start();
if (!isset($_SESSION["auth_id"]) ){ header ("Location:index.php"); }

if(isset($_POST["id"]))
{
    $connect = new PDO('mysql:host=localhost;dbname='.$name, $user, $pass);
 $query = "
 DELETE from events WHERE id=:id and user_id=:user_id
 ";
 $statement = $connect->prepare($query);
 $statement->execute(
  array(
   ':id' => $_POST['id'],
   ':user_id' => $_SESSION["auth_id"]
  )
 );
    $ar = $statement->rowCount();
    if( $ar==0 ){
        login("calendar" ,$MSG[12]." Calendario: ".$_POST['id']."/".$_SESSION["auth_id"]);
    }else{
        login("calendar" ,$MSG[9]." Calendario: ".$_POST['id']."/".$_SESSION["auth_id"]);
    }
}

?>
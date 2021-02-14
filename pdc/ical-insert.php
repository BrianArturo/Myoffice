<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include("inc/config.php");
session_start();
if (!isset($_SESSION["auth_id"]) ){ header ("Location:index.php"); }
$connect = new PDO('mysql:host=localhost;dbname='.$name, $user, $pass);

if(isset($_POST["title"]))
{
 $query = "
 INSERT INTO events 
 (title, start_event, end_event, user_id, color, public,caso_id ) 
 VALUES (:title, :start_event, :end_event, :user_id, :color, :public,:caso_id)
 ";
 $statement = $connect->prepare($query);
 $statement->execute(
  array(
   ':title'  => $_POST['title'],
   ':start_event' => $_POST['start'],
   ':end_event' => $_POST['end'],
   ':user_id' => $_SESSION["auth_id"],
   ':color' => $_POST["color"],
   ':public' => $_POST["public"],
   ':caso_id' => $_POST["caso_id"]
  )
 );
$insert_id =  $connect->lastInsertId(); 
 login("calendar" ,$MSG[10]." Calendario: ".$insert_id."/".$_SESSION["auth_id"]);
}
//print_r($_POST);

?>
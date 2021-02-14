<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include("inc/config.php");
session_start();
if (!isset($_SESSION["auth_id"]) ){ header ("Location:index.php"); }
$connect = new PDO('mysql:host=localhost;dbname='.$name, $user, $pass);

$data = array();

$query = "SELECT * FROM events where user_id ='".$_SESSION["auth_id"]."' ORDER BY id";

$statement = $connect->prepare($query);

$statement->execute();

$result = $statement->fetchAll();

foreach($result as $row)
{
 $data[] = array(
  'id'   => $row["id"],
  'title'   => $row["title"],
  #'start'   =>  date('h:i:s a m/d/Y', strtotime($row["start_event"])),
  'start'   =>  $row["start_event"],
  #'end'   => date('h:i:s a m/d/Y', strtotime($row["end_event"])), 
  'end'   => $row["end_event"], 
  /*'url'   => "dashboard.php?id=".$row['id'],*/
  'color'   => $row["color"],
  'client_id'   => rawurlencode(base64_encode(auyama_encrypt($row["client_id"]))),
  'caso_id'   => rawurlencode(base64_encode(auyama_encrypt($row["caso_id"])))
 );
}

echo json_encode($data);

?>
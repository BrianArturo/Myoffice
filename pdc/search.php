<?php
include("inc/config.php");
session_start();
if (!isset($_SESSION["auth_id"]) ){ header ("Location:index.php"); }
if($_GET['logout']=="logout"){ unset($_SESSION); }

header('Content-Type: application/json');


$query = "SELECT * from contacts where name like '%".trim($_GET["term"])."%' and created_by='".$_SESSION["auth_id"]."' or created_by='".$_SESSION["created_by"]."' order by name desc ";

$mysqli->real_query($query);
$res = $mysqli->use_result();

while ($linea = $res->fetch_assoc()) {
	$data[] =array("id"=>$linea["contact_id"],  "label"=>$linea["name"] , "value"=>$linea["name"]);
 
}
echo json_encode($data);
/*
[{"id":"Botaurus stellaris","label":"Great Bittern","value":"Great Bittern"},
{"id":"Asio flammeus","label":"Short-eared Owl","value":"Short-eared Owl"},
{"id":"Caprimulgus europaeus","label":"European Nightjar","value":"European Nightjar"},
{"id":"Picus viridis","label":"European Green Woodpecker","value":"European Green Woodpecker"},
{"id":"Oxyura leucocephala","label":"White-headed Duck","value":"White-headed Duck"},
{"id":"Saxicola rubicola","label":"European Stonechat","value":"European Stonechat"},
{"id":"Oenanthe oenanthe","label":"Northern Wheatear","value":"Northern Wheatear"},
{"id":"Acrocephalus arundinaceus","label":"Great Reed Warbler","value":"Great Reed Warbler"},
{"id":"Panurus biarmicus","label":"Bearded Reedling","value":"Bearded Reedling"},
{"id":"Lanius excubitor","label":"Great Grey Shrike","value":"Great Grey Shrike"},
{"id":"Phalacrocorax carbo","label":"Great Cormorant","value":"Great Cormorant"},
{"id":"Podiceps cristatus","label":"Great Crested Grebe","value":"Great Crested Grebe"}]
*/
?>
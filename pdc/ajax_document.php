<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include("inc/config.php");
session_start();
if (!isset($_SESSION["auth_id"]) ){ header ("Location:index.php"); }
if($_GET['logout']=="logout"){ unset($_SESSION); }

$QUERY_SETTINGS="SELECT * FROM settings";
$mysqli->real_query($QUERY_SETTINGS);
$result = $mysqli->use_result();

while ($row = $result->fetch_assoc()) {
$s[$row["name"]]=$row["value"];
}
$attachment_allow   =   str_replace(array(".",","),array("","|"),$s["attachment_allow"]);
require('inc/UploadHandler.php');

#error_reporting(E_ALL | E_STRICT);
class CustomUploadHandler extends UploadHandler {
    protected function get_user_id() {
        # questa classe e per user
        $id = auyama_decrypt(base64_decode(rawurldecode ($_REQUEST["caso_id"])));
        
        #return $_SESSION["auth_id"];
        return $id;
    }
}

$upload_handler = new CustomUploadHandler(array(
    'user_dirs' => true
));
?> 
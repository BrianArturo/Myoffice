<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include("inc/config.php");
session_start();
if (!isset($_SESSION["auth_id"]) ){ header ("Location:index.php"); }
if($_GET['logout']=="logout"){ unset($_SESSION); }
?>
<?php
$myuser = new mysqli($host, $user, $pass, $name);
$_ID = auyama_decrypt(base64_decode(rawurldecode ($_GET["id"])));
$_STATUS = auyama_decrypt(base64_decode(rawurldecode ($_GET["status"])));
//var_dump($_STATUS);exit();
$myuser = new mysqli($host, $user, $pass, $name);
if($_STATUS=='enable')
{
    $query = "UPDATE users SET status = 'disable' WHERE id='".$_ID."' or created_by='".$_ID."'";
}
else{
    $query = "UPDATE users SET status = 'enable' WHERE id='".$_ID."' or created_by='".$_ID."'";
}

$myuser->real_query($query);
$myuser->close();
header("Location: {$_SERVER['HTTP_REFERER']}");
die();
?>
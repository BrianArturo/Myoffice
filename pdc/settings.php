<?php
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    include("inc/config.php");
    session_start();

    $_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
    $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    
    $result = $mysqli->query("select * from user_setting where id='".$_SESSION["auth_id"]."' and var='".$_POST["var"]."';");
    $row_cnt = $result->num_rows;

    if($row_cnt>0){
        $SQL_update ="update user_setting set var='".$_POST["var"]."', val='".$_POST["val"]."' where (id='".$_SESSION["auth_id"]."' and var='".$_POST["var"]."')";
        $mysqli->query($SQL_update);
       echo  $SQL_update;
    }else{
        $SQL_insert ="insert into user_setting  values('".$_SESSION["auth_id"]."' , '".$_POST["val"]."' , '".$_POST["var"]."');";
        $mysqli->query($SQL_insert);
       echo  $SQL_insert;
    }



?>

<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include("inc/config.php");
session_start();
if (!isset($_SESSION["auth_id"]) ){ header ("Location:index.php"); }
if($_GET['logout']=="logout"){ unset($_SESSION); }

$caso_id = auyama_decrypt(base64_decode(rawurldecode ($_REQUEST["caso_id"])));

if( $_POST["function"]=="add_document"){
    $mysqli3 = new mysqli($host, $user, $pass, $name);
    $name_file       =   $_POST["name"];
    $realname   =   $_POST["realname"];
    $extention  =   $ext = pathinfo($realname, PATHINFO_EXTENSION);
    //echo $name_file;
    $SQL_ADD_DOCUMENTS  =   "INSERT INTO documents ".
                            "(document_id, caso_id, name, realname, extention, user_id) ".
                            "VALUES (NULL ,'".$caso_id."','".$name_file."','".$realname."','".$extention."',".$_SESSION["auth_id"].") ".
                            "";
    
    $mysqli3->real_query($SQL_ADD_DOCUMENTS);
    $mysqli3->close();
    //var_dump($SQL_ADD_DOCUMENTS);exit();
    $mysqli2->close();
    $mysqli2 = new mysqli($host, $user, $pass, $name);
    $document_count_sql = "SELECT COUNT(*) as total FROM documents WHERE caso_id='".$caso_id."'";
    $mysqli2->real_query($document_count_sql);
    $documentcount = $mysqli2->use_result();
    $documentcount = $documentcount->fetch_assoc();
    echo $documentcount["total"];
   

}

if( $_POST["function"]=="list_document" ){

    $SQL_DOCUMENTS="SELECT * FROM  documents   where documents.caso_id=".$caso_id."  order by extention";
   // echo $SQL_DOCUMENTS;
    $mysqli2->real_query($SQL_DOCUMENTS);
    $contacts = $mysqli2->use_result();
    while ($u = $contacts->fetch_assoc()) {
     
      if(strtolower($u["extention"])=="jpg" or $u["extention"]=="png" or $u["extention"]=="jpeg" )
      {
        echo  '<div class="  col-6 col-lg-1 p-lg-2 m-lg-2 mb-2 text-center  ">'.
                '<a href="../download/'.$u["caso_id"].'/'.$u["name"].'" class="fancy'.strtolower($u["extention"]).'" target="blank">
                <div class=" ">
                <img src="../download/'.$u["caso_id"].'/'.$u["name"].'" style="width:50px;height:50px;">'.
                  '<div class="m-1 text-truncate ">'. $u["realname"].'</div>'.
                '</div></img></a>'.
                '<i class="fa fa-trash delete-file fa-2x"  file-id="'. $u["document_id"].'"  aria-hidden="true"  ></i>'.
              '</div>';
            }else{
              echo  '<div class="  col-6 col-lg-1 p-lg-2 m-lg-2 mb-2 text-center  ">'.
              '<a href="../download/'.$u["caso_id"].'/'.$u["name"].'" class="fancy'.strtolower($u["extention"]).'" target="blank">'.
                '<div class=" "><i class="fa  fa-file '.strtolower($u["extention"]).'  fa-3x" aria-hidden="true"  ></i></div>'.
                '<div class="m-1 text-truncate ">'. $u["realname"].'</div>'.
              '</a>'.
              '<i class="fa fa-trash delete-file fa-2x"  file-id="'. $u["document_id"].'"  aria-hidden="true"  ></i>'.
            '</div>';
          }
    } 
}

if( $_POST["function"]=="delete_document" AND is_numeric($_POST["file_id"] )){

     $file_id       =    $mysqli->real_escape_string( $_POST["file_id"]);
   
    $SELECT_FILE_INFO="SELECT * FROM  documents where document_id=".$file_id." AND caso_id=".$caso_id."";
    $mysqli2->real_query($SELECT_FILE_INFO);
    $files = $mysqli2->use_result();
    $f = $files->fetch_assoc();
    $_FILE_TO_DELETE=  $DOCUMENT_FILE_DIRECTORY."/".$f["caso_id"]."/".$f["name"];
    #echo "il file essiste ".$_FILE_TO_DELETE;
    if(file_exists($_FILE_TO_DELETE)){
     //echo "il file essiste ".$_FILE_TO_DELETE;
      $DELETE_FILE="DELETE FROM documents where document_id=".$file_id." AND caso_id=".$caso_id." limit 1 ";
      //echo $DELETE_FILE."";
      $mysqli->real_query($DELETE_FILE);
      //var_dump($mysqli);
      #echo "cancello il file dal DB";
      unlink($_FILE_TO_DELETE);
      //echo "il file è stato cancellato";
    }
    $mysqli2->close();
    $mysqli2 = new mysqli($host, $user, $pass, $name);
    $document_count_sql = "SELECT COUNT(*) as total FROM documents WHERE caso_id='".$caso_id."'";
    $mysqli2->real_query($document_count_sql);
    $documentcount = $mysqli2->use_result();
    $documentcount = $documentcount->fetch_assoc();
    echo $documentcount["total"];
  }
?>

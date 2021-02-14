<?php
 session_start();
 if (!isset($_SESSION["ospite"]) ){ die("<b>404 File not found or you are not login!</b>");  }
include("../pdc/inc/config.php");
$id = auyama_decrypt(base64_decode(rawurldecode($_GET["id"])));

$mysqliu = new mysqli($host, $user, $pass, $name);
$QUERY_DOCUMENTOS = "SELECT * from documents where document_id='".$id."'";
$mysqliu->real_query($QUERY_DOCUMENTOS);
$result = $mysqliu->use_result();
$f = $result->fetch_assoc();  
$filename=$f["name"];
$path="../download/".$f["caso_id"]."/";

$path_filename=$path.$filename;

if (!is_file($path_filename)) { die("<b>404 File $path_filename   not found!</b>"); }


   $len = filesize($path_filename);
   $filename = basename($path_filename);
   $file_extension = strtolower(substr(strrchr($filename,"."),1));

   //This will set the Content-Type to the appropriate setting for the file
   switch( $file_extension ) {
         case "pdf": $ctype="application/pdf"; break;
     case "exe": $ctype="application/octet-stream"; break;
     case "zip": $ctype="application/zip"; break;
     case "doc": $ctype="application/msword"; break;
     case "xls": $ctype="application/vnd.ms-excel"; break;
     case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
     case "gif": $ctype="image/gif"; break;
     case "png": $ctype="image/png"; break;
     case "jpeg":
     case "jpg": $ctype="image/jpg"; break;
     case "mp3": $ctype="audio/mpeg"; break;
     case "wav": $ctype="audio/x-wav"; break;
     case "mpeg":
     case "mpg":
     case "mpe": $ctype="video/mpeg"; break;
     case "mov": $ctype="video/quicktime"; break;
     case "avi": $ctype="video/x-msvideo"; break;

     //The following are for extensions that shouldn't be downloaded (sensitive stuff, like php files)
     case "php":
     case "htm":
     case "html":
     case "txt": die("<b>Cannot be used for ". $file_extension ." files!</b>"); break;

     default: $ctype="application/force-download";
   }


  //Begin writing headers
   header("Pragma: public");
   #header("Expires: 0");
   header("Cache-Control: must-revalidate, post-check=0, pre-check=0, no-store,");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
   header("Cache-control: public");
   header("Content-Description: File Transfer");

   //Use the switch-generated Content-Type
   header("Content-Type: $ctype");

   //Force the download
   $header="Content-Disposition: attachment; filename=".$filename.";";
   header($header );
   header("Content-Transfer-Encoding: binary");
   header("Content-Length: ".$len);


   $fp = @fopen($path_filename,"r");
   $filedata=@fread($fp,filesize($path_filename));
   fclose($fp);
   print $filedata;
   exit;
/*
            header('Pragma: private');
            header('Cache-control: private, must-revalidate');
            header("Content-type: text/x-ms-iqy");
    $fp = fopen($path_filename,"r");
    $filedata=fread($fp,filesize($path_filename));
     fclose($fp);
            header("Content-type: text/x-ms-iqy");
            header("Content-Disposition: attachment; filename=".$filename);
print $filedata;*/
?>

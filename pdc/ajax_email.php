<?php
include("inc/config.php");
require("mailer/class.phpmailer.php");
require("mailer/phpmailer.lang-it.php");
session_start();
if (!isset($_SESSION["auth_id"]) ){ header ("Location:index.php"); }
if($_GET['logout']=="logout"){ unset($_SESSION); }

 

$caso_id    = $mysqli2->real_escape_string(auyama_decrypt(base64_decode(rawurldecode ($_POST["caso_id"]))));
$contac_id  = $mysqli2->real_escape_string(auyama_decrypt(base64_decode(rawurldecode ($_POST["contac_id"]))));
 
 
    if( $_POST["function"]=="mail_invitation" ){


        $QUERY_SETTINGS="SELECT * FROM settings";
        $mysqli->real_query($QUERY_SETTINGS);
        $result = $mysqli->use_result();
        
        while ($row = $result->fetch_assoc()) {
          $s[$row["name"]]=$row["value"];
          #echo $row["name"]." - > ".$row["value"]."<br>";
        }
        $contacts_type = explode(",",$s["contacts_type"]);
        foreach($contacts_type as $c){
          $contacts_type_key = explode(":",$c);
          # echo $contacts_type_key[0]." ->> ".$contacts_type_key[1]."<br>";
          $contact_type[$contacts_type_key[0]]=$contacts_type_key[1];
        }
        $status_caso = explode(",",$s["status_caso"]);
        foreach($status_caso as $n){
          $status_caso_key = explode(":",$n);
          #echo $status_caso_key[0]." ->> ".$status_caso_key[1]."<br>";
          $status_caso_array[$status_caso_key[0]]=$status_caso_key[1];
        }


/*
        echo "url       ". $s["url"]."\n";
        echo "Company   ". $s["company"]."\n";
        echo "Phone     ". $s["phone"]."\n";
        echo "url       ". $s["url"]."\n";
        echo "url_logo                      ". $s["url_logo"]."\n";
        echo "email_template_subject        ". $s["email_template_subject"]."\n";
        echo "email_template_body           ". $s["email_template_body"]."\n";
        echo "Contact:  ".$caso_id."\n";;
       Lb3uz16_UB24
*/



        
        $SELECT_CONTACT="SELECT * from contacts where contact_id='".$contac_id."'";    
        $mysqli->real_query($SELECT_CONTACT);
        $result = $mysqli->use_result();
        $contact = $result->fetch_assoc();
        $mysqli->close();
        #echo "Contatto:  ".$contact["name"]."\n";;

        
        $SELECT_CASO="SELECT * from casos where caso_id='".$caso_id."'";
        $mysqli2->real_query($SELECT_CASO);
        $result = $mysqli2->use_result();

        $caso = $result->fetch_assoc();
        #echo "Caso:  ".$caso["name"]."\n";;


        $email      =$contact["email"];
        $name_to       =$contact["name"];
        $_body      ="".$s["email_template_body"];
        $_subject   ="".$s["email_template_subject"];
        $password   =randomPassword();

        $array_from=array("%nombre%","%password%","%link%","%caso_id%");
        $array_to=array( 
          $name,$password,
          $s["url"]."client/".str_replace("%2F","-nube24-",rawurlencode(base64_encode(auyama_encrypt($caso_id)))).".html",
          $caso_id
        );
        $_body  = utf8_decode(str_replace($array_from,$array_to,$_body ));
        $_subject  = utf8_decode(str_replace($array_from,$array_to,$_subject ));
        login('email' ,$MSG[7]);
        $update_password = new mysqli($host, $user, $pass, $name);
        $clean_password=$update_password->real_escape_string($password);
        $update_password->real_query("UPDATE contacts set password='".$clean_password."' where contact_id=".$contac_id);
 
  $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SetLanguage("it","mailer/" );
        $mail->Host       = $smtp_host;
        $mail->SMTPAuth   = false;
        //¿$mail->Port       = $port;
        $mail->SMTPSecure = false;
        //$mail->Username   = $smtp_user;
        //$mail->Password   = $smtp_pass;
        $mail->setFrom     = $email_from;
        $mail->FromName = $email_name;
        $mail->AddAddress($email, $name_to);
        $mail->AddAddress($email_to, $email_name);
        //$mail->AddBCC($email_bcc, $name_bcc);
        $mail->WordWrap = 50;
        $mail->IsHTML(true);
        $mail->Subject = $_subject;
        $mail->Body    = $_body;
        $mail->AltBody = strip_tags($_body);
        $mail->SMTPDebug = 4;
        if(!$mail->Send())
        {
           echo "Message could not be sent. <p>";
           echo "Mailer Error: " . $mail->ErrorInfo;
           exit;
        }
 












    }

 

  
?>

<?php 
include("inc/config.php");
require("mailer/class.phpmailer.php");
require("mailer/phpmailer.lang-it.php");

$secure_time=3600;
$recover=false;
$change=false;  
    //7200  2 oras
    //120 2 munutos
    //240 4 munutos
$QUERY_SETTINGS="SELECT * FROM settings";
$mysqli->real_query($QUERY_SETTINGS);
$result = $mysqli->use_result();

while ($row = $result->fetch_assoc()) {
  $s[$row["name"]]=$row["value"];
}



$input_email =  $mysqli->real_escape_string(trim($_POST["email"]));
$query = "SELECT * from users where email='".$input_email."' limit 1 ";
  
$mysqli->real_query($query);
$res = $mysqli->use_result();

while ($linea = $res->fetch_assoc()) {
    $email			=$linea["email"];
    $auth			=$linea["auth"];
    $name			=$linea["name"];
}

if($email==$input_email && isset($_POST["email"])){

    $QUERY_SETTINGS="SELECT * FROM settings";
    $mysqli->real_query($QUERY_SETTINGS);
    $result = $mysqli->use_result();
    
    while ($row = $result->fetch_assoc()) {
      $s[$row["name"]]=$row["value"];
      #echo $row["name"]." - > ".$row["value"]."<br>";
    }

 
   
    $_subject   = $s["company"].": Recuperacion de la passord";
    $_body      =   "Hola , ".
                    "haz pedido la recuperacion de tu password.<br>".
                    "para seguir con el procedimiento sigue este link:<br>".
                    "<a href=\"%link%\">recupera password</a><br><br>".
                    "en caso contrario no tienes que hacer nada<br><br>".
                    "Datos de la riquiesta:<br>".
                    "IP/Date: ".$_SERVER['REMOTE_ADDR']." - ".date("d/m/Y")."<br>".
                    "<br>";
    $key    =   str_replace("%2F","movil",rawurlencode(base64_encode(auyama_encrypt(time().":".$email))));
    $linkauth=$s["url"]."pdc/recover.php?auth=".$key;
    $_body      = utf8_decode(str_replace("%link%",$linkauth,$_body));
    $_subject   = utf8_decode($_subject);

// posso mandare lemail ancora

    if( (($auth + $secure_time )< time()) || ($auth==0) ){
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SetLanguage("it","mailer/" );
        $mail->Host       = $smtp_host;
        $mail->SMTPAuth   = true;
        $mail->Port       = $port;
        $mail->SMTPSecure = $secure;
        $mail->Username   = $smtp_user;
        $mail->Password   = $smtp_pass;
        $mail->From     = $email_from;
        $mail->FromName = $email_name;
        $mail->AddAddress($email, $name);
#        $mail->AddBCC($email_bcc, $name_bcc);
        $mail->WordWrap = 50;
        $mail->IsHTML(true);
        $mail->Subject = $_subject;
        $mail->Body    = $_body;
        $mail->AltBody = strip_tags($_body);
        if(!$mail->Send())
        {
        echo "Message could not be sent. <p>";
        echo "Mailer Error: " . $mail->ErrorInfo;
        exit;
        }
        $html.="Revisa tu correo.<br>";
        $query_update = "UPDATE users set  auth='".time()."'  where email='".$email."'  ";
        $mysqli2->real_query($query_update);
    }else{
    //  abiamo già inviato una richiesta

        $html.="Ya hay un cambio de password en proseso.<br>";


    }
}else{
    #$html.="La direccion E-mail no es valida.<br>";
}

if(isset($_GET["auth"])){
    $auth=auyama_decrypt(base64_decode(rawurldecode (str_replace("-movil-", "%2F",$_GET["auth"]))));
    $auth_key=explode(":",$auth);
    if(
            ((time() - $auth_key[0] ) <$secure_time )
        &&
            (filter_var($auth_key[1], FILTER_VALIDATE_EMAIL))
        ){
            $html.="auth correcta e email valida <br>";
            $recover=true;
            if(isset($_POST["change"])){
                $recover=false;   
                $change=true;   
                $query_password = "UPDATE users set password='".md5($_POST["password"])."' where email='".$auth_key[1]."' limit 1 ";                
                $mysqli->real_query($query_password);               


            }


    }else{
            $html.="Autorizaciòn vencida o email non valida.<br>";

    }
}

?>
<!DOCTYPE HTML>
<head>
    <title><?php echo $TITULO ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <script src="//code.jquery.com/jquery-3.3.1.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
 <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
   
    <?php include("inc/header.php");?>
</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
    <div class="container-fluid" style="max-width:1450px;margin-top:50px;">
        <div id="content">
            <div class="row">
                <div class="col-md-4 card card-container">
                    <p class="text-center"><img id="profile-img" class="profile-img-card" src="images/logo.png" style="max-width:150px;"/></p>
                    <p id="profile-name" class="profile-name-card"></p>
                    <form action="" method='POST' enctype='multipart/form-data' class="form-signin">

                    <?php if($recover){ ?>
                        <div class="form-group">
                        <label>
                        La nueva password debe ser larga, maximo 12 caracteres y minimo 8.<br>
                        Contener mayusculas/minusculas y caracteres especiales !@#$%^&*_=+-
                        </label>
                        <input class="form-control form-control-lg" type="text" name="password" title="Maximo/min 12/8 maiuscula/minuscula y !@#$%^&*_=+-" placeholder="Solo si se quiere cambiar la password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*_=+-]).{8,12}$" required autofocus>
                        </div>
                        <button class="btn btn-lg btn-primary btn-block btn-signin" id="change"  name="change" type="submit">Cambia la password</button>
                    <?php }elseif($change){ ?>
                        <div class="form-group">
                            <a href="index.php" class="text-white"> Password cambiada, ahora puedes ingresar al sistema clicando aqui.</a>
                        </div>
                    <?php 
                    }else{ 
                    ?>
                        <div class="form-group">
                            <input type="email" name="email" class="form-control form-control-lg" placeholder="Email address" required autofocus>
                        </div>
                        <button class="btn btn-lg btn-primary btn-block btn-signin" id="recover"  name="recover" type="submit">Recover</button>
                    <?php
                    }
                    ?>

                        <div><?php echo $html; ?></div>
                        
                    </form>
 
                </div>
                <!-- /card-container -->
            </div>
        </div>
    </div>
    <div id="footerWrap" class="wrap">
        <footer class="container">
            <nav id="tools">
                <!-- heare some footer stuff -->
            </nav>
        </footer>
    </div>
    <?php

    ?>
	<?php /*include("inc/footer.php");*/ ?>
</body>
</html>

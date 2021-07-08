<?php include("inc/config.php");


if ($_GET["logout"] == "true") {
    session_start();
    session_destroy();
    setcookie("PHPSESSID", "", time() - 3600, "/");
}




if (isset($_POST["google-response-token"])) {

    if (empty($_POST["email"])  || empty($_POST["password"])) {
    } else {

        $googleToken = $_POST['google-response-token'];

        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LejtnsbAAAAAMgHn5WgGaN5svno2kW3P_0sl7G8&response={$googleToken}");
        $response = json_decode($response);

        $response = (array) $response;


        if ($response['success'] && ($response['score'] && $response['score'] > 0.6)) {

            $user = trim($_POST["email"]);
            $pass = trim($_POST["password"]);




            $vowels = array(";", ",", " ");
            $user =  $mysqli->real_escape_string(str_replace($vowels, "", $user));
            $pass =  $mysqli->real_escape_string(str_replace($vowels, "", $pass));
            $query = "SELECT * from users where email='" . $user . "' AND password=MD5('" . $pass . "') AND status='enable'  limit 1";

            $mysqli->real_query($query);
            $res = $mysqli->use_result();

            while ($linea = $res->fetch_assoc()) {


                $id                = $linea["id"];
                $email            = $linea["email"];
                $name            = $linea["name"];
                $company        = $linea["company"];
                $type            = $linea["type"];
                $reseller_id    = $linea["reseller"];
                $created_by        = $linea["created_by"];
            }

            if ($user == $email) {
                session_start();

                $_SESSION["auth_id"]        = $id;
                $_SESSION["reseller_id"]    = $reseller_id;
                $_SESSION["email"]            = $email;
                $_SESSION["type"]            = $type;
                $_SESSION["sezione"]        = $company;
                $_SESSION["created_by"]        = $created_by;

                login("login", $MSG[1]);
                if ($type == "guest") {
                    header("Location: casos.php");
                } else {
                    header("Location: casos.php");
                }
            } else {
                login("login", $MSG[2]);
            }
        } else {
        }
    }
} else {
}

?>
<!DOCTYPE HTML>

<head>
    <title><?php echo $TITULO ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <script src="//code.jquery.com/jquery-3.3.1.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://www.google.com/recaptcha/api.js?render=6LejtnsbAAAAAMgHn5WgGaN5svno2kW3P_0sl7G8"></script>

    <script>
        function onSubmit(token) {
            $('#google-response-token').val(token);
            document.getElementById("form1").submit();
        }
    </script>

    <?php include("inc/header.php"); ?>
</head>

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
    <div class="container-fluid" style="max-width:1450px;margin-top:50px;">
        <div id="content">
            <div class="row">
                <div class="col-md-4 card card-container">
                    <p class="text-center"><img id="profile-img" class="profile-img-card" src="images/logo.png" style="max-width:250px;" /></p>
                    <p id="profile-name" class="profile-name-card"></p>
                    <form action="index.php" method='POST' enctype='multipart/form-data' class="form-signin" id="form1">
                        <div class="form-group">
                            <input type="email" name="email" class="form-control form-control-lg" placeholder="Email address" required autofocus>
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" class="form-control form-control-lg" placeholder="Password" required>
                        </div>
                        <div id="remember" class="checkbox">
                            <label>
                                <input type="checkbox" value="remember-me"> Remember me
                            </label>
                        </div>
                        <input type="hidden" name="google-response-token" id="google-response-token">
                        <!--<button class="btn btn-lg btn-primary btn-block btn-signin" id="signin" name="signin" type="submit">Sign in</button>-->
                        <button class="btn btn-lg btn-primary btn-block btn-signin g-recaptcha" data-sitekey="6LejtnsbAAAAAAKXaaR7Hb5veNyQ06F9HvHap5ti" data-callback='onSubmit' data-action='submit'>Sign in</button>
                    </form>
                    <!-- /form -->
                    <a href="recover.php" class="forgot-password">Olvidaste tu contraseña?</a>
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
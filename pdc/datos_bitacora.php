<?php
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    include("inc/config.php");
    session_start();
    if (!isset($_SESSION["auth_id"]) ){ header ("Location:index.php"); }
    if($_GET['logout']=="logout"){ unset($_SESSION); }

    $mysqli=new mysqli($host, $user, $pass, $name);
    $id_caso = $_GET['id_caso'];

    $SQL_chk    = "SELECT b.id, u.name, b.modulo, b.fecha, b.descripcion FROM bitacora as b, users as u WHERE id_caso='".$id_caso."' and b.id_usuario= u.id" ;
    $mysqli->real_query($SQL_chk);
    $res = $mysqli->use_result();
    //$datos = $res->fetch_assoc();
    //print_r($SQL_chk);
   // print_r($datos);

?>
<?php include("inc/header.php"); ?>
<?php include("inc/menu.php"); ?>

<!DOCTYPE HTML>
<head>
    <title><?php echo $TITULO ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<div class="container-fluid" style="max-width:1450px;margin-top:20px;">
    <div id="content" class="wrapper">
        <div class="row">
            <div class="col-md-10"><h3>Bitacora De los Documentos</h3></div>
			<div class="col-md-2" style="center">	
				<a href="javascript: history.go(-1)">
						<button type="button" class="btn btn-primary mt-2 float-right">REGRESAR AL CASO</button>
					</a>
				
			</div>
			
        </div>


    </div> <br>
</div>

<div class="container-fluid mb-5" style="max-width:1450px; ">
    <div id="content" class="wrapper">
        <table id="contatti" class="table table-striped table-bordered display" style="width:100%">
            <thead>
            <tr>
                <th>ID</th>
                <th>FECHA</th>
                <th>USUARIO</th>
                <th>SECCIÓN</th>
                <th>DESCRIPCION</th>
            </tr>
            </thead>
            <tbody>
                <?php
                $i=0;
                while ($datos = $res->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>'. $datos["id"].'</td>';
                    echo '<td>'. $datos["fecha"].'</td>';
                    echo '<td>'. $datos["name"].'</td>';
                    echo '<td>'. $datos["modulo"].'</td>';
                    echo '<td>'. $datos["descripcion"].'</td>';
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php
if($_SESSION["type"] !="guest"){
    include("inc/footer.php");
}?>
<script type="text/javascript" src="js/script.js"></script>
<script type="text/javascript" src="js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="js/jszip.min.js"></script>
<script type="text/javascript" src="js/pdfmake.min.js"></script>
<script type="text/javascript" src="js/vfs_fonts.js"></script>
<script type="text/javascript" src="js/buttons.html5.min.js"></script>


<!--<script type="module" src="milsymbol-2.0.0/src/milsymbol.js"></script> -->

</body>
</html>

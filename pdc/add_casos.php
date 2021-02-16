<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include("inc/config.php");
session_start();
if (!isset($_SESSION["auth_id"])) {
  header("Location:index.php");
}
if ($_GET['logout'] == "logout") {
  unset($_SESSION);
}

/*
  SETTINGS
*/
$QUERY_SETTINGS = "SELECT * FROM settings where owned_by = '" . $_SESSION["auth_id"] . "'or owned_by = '" . $_SESSION["created_by"] . "'";
$mysqli->real_query($QUERY_SETTINGS);
$result = $mysqli->use_result();

while ($row = $result->fetch_assoc()) {
  $s[$row["name"]] = $row["value"];
  #echo $row["name"]." - > ".$row["value"]."<br>";
}
$contacts_type = explode(",", $s["status_caso"]);
foreach ($contacts_type as $c) {
  $contacts_type_key = explode(":", $c);
  # echo $contacts_type_key[0]." ->> ".$contacts_type_key[1]."<br>";
  $status_caso[$contacts_type_key[0]] = $contacts_type_key[1];
}
/*
  FIN SETTINGS
*/
?>
<!DOCTYPE HTML>

<head>
  <title><?php echo $TITULO ?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <?php include("inc/header.php"); ?>


</head>

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">



  <?php include("inc/menu.php"); ?>
  <div class="container-fluid maxwidth">
    <div id="content" class="wrapper">
      <div class="row">
        <div class="col-md-6 mt-3">
          <h3>Nuevo Caso</h3>
        </div>
        <div class="col-md-6 mt-3 text-right"> </div>
        <div class="col-md-12">
          <hr>
        </div>

      </div>
    </div>
  </div>




  <form method="post" id="contact_form" name="contact_form">
    <div class="container-fluid maxwidth">
      <div id="content" class="wrapper">
        <div class="row">


          <div class="col-md-4">
            <div class="form-group">
              <label for="name">Nombre:</label>
              <input type="text" class="form-control" id="name" name="name" placeholder="Nombre del caso" required>
              <input type="hidden" id="created_by" name="created_by" value="<?php echo $_SESSION["auth_id"]; ?>">
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label for="code">Codigo:</label>
              <input type="text" class="form-control" id="code" name="code" placeholder="Codigo del caso" required>
            </div>
          </div>

          <div class="col-md-4">
            <label for="status">Estado:</label>
            <select class="form-control custom-select" id="status" name="status" required>
              <option value="" disabled selected>Selecciona el estado inicial</option>
              <?php
              foreach ($status_caso as $k => $v) {
                echo '<option value="' . $k . '">' . $v . '</option>';
              }
              ?>
            </select>
            <input type="hidden" id="desstatus" name="desstatus">
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="code">Cuantia:</label>
              <input data-type="currency" type="text" class="form-control" id="cuantia" name="cuantia" placeholder="Cuantia" required>
            </div>
          </div>
          <div class="col-md-12">
            <label for="notes">Descripcion del caso:</label>
            <textarea name="notes" id="notes" class="notes"> </textarea>
          </div>


          <div class="col-md-12">
            <button type="submit" id="new_caso" name="new_caso" class="btn btn-primary">CREA EL CASO</button>
          </div>
          <div class="col-md-12 alert alert-success text-center hidden4ajax" id="log"> </div>
          <div class="col-md-12 text-center hidden4ajax" id="spinner">
            <i class="fa fa-spinner fa-3x fa-fw fa-spin" aria-hidden="true"></i> Guardando las modificaciones...
          </div>
        </div>
      </div>
    </div>
  </form>


  <?php include("inc/footer.php"); ?>
  <script type="text/javascript" src="js/script.js"></script>
  <script type="text/javascript">
    var form = document.getElementById('contact_form');
    form.elements.status.onchange = function() {
      var option = this.options[this.selectedIndex];
      this.form.elements.desstatus.value = option.innerHTML;
    }
  </script>
</body>

</html>
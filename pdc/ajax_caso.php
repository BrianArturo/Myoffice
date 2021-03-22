<?php
include('inc/config.php');
include('inc/bitacora.php');
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
$QUERY_SETTINGS = "SELECT * FROM settings";
$mysqli->real_query($QUERY_SETTINGS);
$result = $mysqli->use_result();

while ($row = $result->fetch_assoc()) {
  $s[$row["name"]] = $row["value"];
  #echo $row["name"]." - > ".$row["value"]."<br>";
}
$contacts_type = explode(",", $s["contacts_type"]);
foreach ($contacts_type as $c) {
  $contacts_type_key = explode(":", $c);
  # echo $contacts_type_key[0]." ->> ".$contacts_type_key[1]."<br>";
  $key[$contacts_type_key[0]] = $contacts_type_key[1];
}
/*
FIN SETTINGS
*/
$_ID = auyama_decrypt(base64_decode(rawurldecode($_POST["caso_id"])));

if ($_POST["function"] == "add_client") {

  $query = "INSERT INTO casos_contacts ( caso_id, contact_id, contact_type) values('" . $_ID . "','" . $_POST["client_id"] . "','1' );  ";
  #echo $query;
  $mysqli->real_query($query);
  bitacoraNewCaso($_ID, "Se agrego el contacto ".$_POST["client_id"]);

?>
  <div class="row mr-1 ml-1 font-weight-bold d-none d-lg-flex   ">
    <div class="col-md-2"></div>
    <div class="col-md-3">NOMBRE</div>
    <div class="col-md-2">TELEFONO</div>
    <div class="col-md-2">EMAIL</div>
    <div class="col-md-1">NIT</div>
    <div class="col-md-2">RELACIÒN</div>
  </div>
  <?php
  $SQL_CONTACTS = "SELECT * FROM casos_contacts join contacts on casos_contacts.contact_id=contacts.contact_id where caso_id=" . $_ID;

  $mysqli2->real_query($SQL_CONTACTS);
  $contacts = $mysqli2->use_result();

  while ($u = $contacts->fetch_assoc()) {
    echo  '<div class="row mr-1 ml-1">' .
      '<div class="col-md-2" data-toggle="tooltip" data-placement="top" title="Muestra la tarjeta del contacto">' .
      '<button class="btn btn-primary delete-contact" contact-id="' . rawurlencode(base64_encode(auyama_encrypt($u["contact_id"]))) . '"><i class="fa fa-trash   mt-2 " aria-hidden="true" ></i> </button> ' .
      '<a href="view_contact.php?id=' . rawurlencode(base64_encode(auyama_encrypt($u["contact_id"]))) . '">' .
      '<button class="btn btn-primary"> <i class="fa fa-address-book   mt-2" aria-hidden="true"></i> Editar contacto</button>' .
      '</a>' .
      '</div>' .
      '<div class="col-md-3">' . $u["name"] . '</div>' .
      '<div class="col-md-2">' . $u["phone"] . '</div>' .
      '<div class="col-md-2">' . $u["email"] . '</div>' .
      '<div class="col-md-1">' . $u["nit"] . '</div>' .
      '<div class="col-md-2">' . $key[$u["contact_type"]] . '</div>' .
      '</div>';
  }
}

if ($_POST["function"] == "delete_client") {
  $client_id  = auyama_decrypt(base64_decode(rawurldecode($_POST["client_id"])));
  $query      = "DELETE from casos_contacts where caso_id='" . $_ID . "' and contact_id='" . $client_id . "'   ";
  #echo $query;
  $mysqli->real_query($query);
  bitacoraContactos($_ID, 'Se ha eliminado el contacto '.$client_id.' del caso '.$_ID);

  ?>
  <div class="row mr-1 ml-1 font-weight-bold d-none d-lg-flex   ">
    <div class="col-md-2"></div>
    <div class="col-md-2">NOMBRE</div>
    <div class="col-md-2">TELEFONO</div>
    <div class="col-md-2">EMAIL</div>
    <div class="col-md-2">NIT</div>
    <div class="col-md-2">RELACIÒN</div>
  </div>
  <?php
  $SQL_CONTACTS = "SELECT * FROM casos_contacts join contacts on casos_contacts.contact_id=contacts.contact_id where caso_id=" . $_ID;

  $mysqli2->real_query($SQL_CONTACTS);
  $contacts = $mysqli2->use_result();

  while ($u = $contacts->fetch_assoc()) {

    if (!empty($u["email"])) {
      $email = '<a href="mailto:' . $u["email"] . '">' . $u["email"] . '</a>';
    } else {
      $email = "";
    }
    echo  '<div class="row mr-1 ml-1">' .
      '<div class="col-md-2" data-toggle="tooltip" data-placement="top" title="Muestra la tarjeta del contacto">' .
      '<i class="fa fa-trash fa-2x mt-2 delete-contact" aria-hidden="true" contact-id="' . rawurlencode(base64_encode(auyama_encrypt($u["contact_id"]))) . '"></i> ' .
      '<a href="view_contact.php?id=' . rawurlencode(base64_encode(auyama_encrypt($u["contact_id"]))) . '">' .
      '<i class="fa fa-address-book fa-2x mt-2" aria-hidden="true"></i>' .
      '</a>' .
      '</div>' .
      '<div class="col-md-2">' . $u["name"] . '</div>' .
      '<div class="col-md-2">' . $u["phone"] . '</div>' .
      '<div class="col-md-2 text-truncate">' . $email . '</div>' .
      '<div class="col-md-2">' . $u["nit"] . '</div>' .
      '<div class="col-md-2">' . $key[$u["contact_type"]] . '</div>' .
      '</div>';
  }
}



if ($_POST["function"] == "save_caso") {

  $nota_caso = $mysqli2->real_escape_string($_POST["nota_caso"]);
  $SQL_CASO = "UPDATE casos set description ='" . $nota_caso . "' where caso_id=" . $_ID;
  $mysqli2->real_query($SQL_CASO);

  bitacoraUpdateDesc($_ID,  $nota_caso);

  echo '<i class="fa fa-check-circle" aria-hidden="true"></i> Los cambios fueron guardados.';
  sleep(2);
}



if ($_POST["function"] == "new_caso") {
  $name         = $mysqli2->real_escape_string($_POST["name"]);
  $description  = $mysqli2->real_escape_string($_POST["notes"]);
  $status       = $mysqli2->real_escape_string($_POST["status"]);
  $code         = $mysqli2->real_escape_string($_POST["code"]);
  $created_by   = $mysqli2->real_escape_string($_POST["created_by"]);
  $cuantia      = $mysqli2->real_escape_string($_POST["cuantia"]);
  $desstatus    = $mysqli2->real_escape_string($_POST["desstatus"]);
  $conv = array("$" => "", "," => "");
  $cuantia = strtr($cuantia, $conv);

  $SQL_CASO =  " INSERT INTO casos " .
    "(caso_id, name, description, status, code, creation_date, last_update,created_by,cuantia) " .
    "VALUES (NULL,'" . $name . "','" . $description . "','" . $status . "','" . $code . "' , CURRENT_TIMESTAMP, CURRENT_TIMESTAMP,'" . $created_by . "'," . $cuantia . " );";
  $mysqli2->real_query($SQL_CASO);
  #echo $SQL_CASO;
  bitacoraNewCaso($mysqli2->insert_id,  ' NOMBRE ' . $name . ' CODIGO ' . $code . ' ESTADO ' . $desstatus . ' CUANTIA ' . $cuantia . ' DESCRIPCION DEL CASO ' . $description);

  echo '<i class="fa fa-check-circle" aria-hidden="true"></i> los cambios fueron guardados.@' . rawurlencode(base64_encode(auyama_encrypt($mysqli2->insert_id)));
}



if ($_POST["function"] == "add_note") {
  // caso id $_ID
  $note_category  = $mysqli2->real_escape_string($_POST["note_category"]);
  $note_text      = $mysqli2->real_escape_string($_POST["note_text"]);


  $SQL_NOTE =  " INSERT INTO notes " .
    "(notes_id,tags,user_id,status,note,creation ) " .
    "VALUES (NULL,'" . $note_category . "','" . $_SESSION["auth_id"] . "','1','" . $note_text . "' ,  CURRENT_TIMESTAMP);";
  $mysqli2->real_query($SQL_NOTE);

  $note_id = $mysqli2->insert_id;

  $SQL_NOTE_CASO =  "INSERT INTO casos_notes " .
    "(caso_id,notes_id ) " .
    "VALUES ('" . $_ID . "','" . $note_id . "' );";

  $mysqli2->real_query($SQL_NOTE_CASO);



  #echo  $SQL_CASO."<br>";
  #echo   $SQL_NOTE_CASO."<br>";
  #echo '<i class="fa fa-check-circle" aria-hidden="true"></i> los cambios fueron guardados.';

  $SQL_NOTES = "SELECT * FROM casos_notes join notes on casos_notes.notes_id=notes.notes_id where  notes.status>0 and caso_id=" . $_ID . " order by creation asc";

  $mysqli2->real_query($SQL_NOTES);
  $notes = $mysqli2->use_result();

  while ($n = $notes->fetch_assoc()) {
    echo  '<div class="row mr-1 ml-1">' .
      '<div class="col-md-1">' .
      '<i class="fa fa-trash fa-2x delete_note" aria-hidden="true " object="delete_note" item="' . rawurlencode(base64_encode(auyama_encrypt($linea["notes_id"]))) . '"></i>' .
      '</div>' .
      '<div class="col-md-3"><strong>Fecha:</strong> ' . $n["creation"] . '</div>' .
      '<div class="col-md-8 "><strong>Categorias:</strong> ' . $n["tags"] . '</div>' .
      '<div class="col-md-12"><strong>NOTA:</strong> ' . $n["note"] . '</div>' .
      '</div>';
  }

  sleep(2);
}





if ($_POST["function"] == "delete_item") {
  $ITEM_ID = auyama_decrypt(base64_decode(rawurldecode($_POST["item_id"])));

  switch ($_POST["object_name"]) {
    case "delete_caso":
      echo "cancella il caso ::" . $ITEM_ID . "::";
      if (is_numeric($ITEM_ID)) {
        $SQL_DELETE_CASO = "UPDATE casos set status=0 where caso_id=" . $ITEM_ID . " limit 1";
        $mysqli2->real_query($SQL_DELETE_CASO);
        echo $SQL_DELETE_CASO;
        login("delete", $MSG[8] . " Caso: " . $ITEM_ID . "/" . $_SESSION["auth_id"]);
      }
      break;
    case "delete_note":
      echo "cancella nota " . $ITEM_ID;
      if (is_numeric($ITEM_ID)) {
        $SQL_DELETE_NOTES = "UPDATE notes set status=0 where notes_id=" . $ITEM_ID . " limit 1";
        $mysqli2->real_query($SQL_DELETE_NOTES);
        echo $SQL_DELETE_NOTES;
        login("delete", $MSG[8] . " Nota: " . $ITEM_ID . "/" . $_SESSION["auth_id"]);
      }
      break;
    case "delete_contact":
      echo "cancella contatto " . $ITEM_ID;
      if (is_numeric($ITEM_ID)) {
        $SQL_DELETE_CONTACT = "UPDATE contacts set status=0 where contact_id=" . $ITEM_ID . " limit 1";
        $mysqli2->real_query($SQL_DELETE_CONTACT);
        echo $SQL_DELETE_CONTACT;
        login("delete", $MSG[8] . " Contacto: " . $ITEM_ID . "/" . $_SESSION["auth_id"]);
      }
      break;
    default:
      echo "Nothing to delete " . $ITEM_ID;
  }

  echo print_r($_POST);
}




if ($_POST["function"] == "save_status_caso") {

  $status = $mysqli2->real_escape_string($_POST["status"]);
  $desstatus = $mysqli2->real_escape_string($_POST["desstatus"]);
  $SQL_CASO_STATUS = "UPDATE casos set status ='" . $status . "' where caso_id=" . $_ID;
  $mysqli2->real_query($SQL_CASO_STATUS);
  bitacoraUpdateCaso($_ID, $desstatus);
  # echo  $SQL_CASO_STATUS;
  sleep(1);
}
if ($_POST["function"] == "anular_pago") {
  if ($_POST["tipoPago"] != "Otros Pagos") {
    //echo 'Anulando Pago...';
    $conv = array("$" => "", "," => "");
    $cuantia = strtr($_POST["valorPago"], $conv);
    $SQL_ANULAR_PAGO = "UPDATE casos set cuantia = cuantia+'" . $cuantia . "' where caso_id=" . $_ID;
    $mysqli2->real_query($SQL_ANULAR_PAGO);
    $mysqli2->close();

  }
  $mysqli2 = new mysqli($host, $user, $pass, $name);
  $SQL_ANULAR_PAGO = "UPDATE pagos set anulado = 1 where pago_id=" . $_POST["pagoId"];
  $mysqli2->real_query($SQL_ANULAR_PAGO);
  $mysqli2->close();
  sleep(1);
  bitacoraPagosAnular($_ID, " Se anulo el pago ".$_POST["pagoId"]." con valor ".$_POST["valorPago"]." tipo de pago ".$_POST["tipoPago"]." del caso ".$_ID);
}



if ($_POST["function"] == "save_contact_type") {

  $contact_type = $mysqli2->real_escape_string($_POST["contact_type"]);
  $contact_id   = $mysqli2->real_escape_string($_POST["contact_id"]);
  $SQL_CONTACT_STATUS = "UPDATE casos_contacts set contact_type ='" . $contact_type . "' where caso_id=" . $_ID . " and contact_id=" . $contact_id;
  $mysqli2->real_query($SQL_CONTACT_STATUS);
  echo  $SQL_CONTACT_STATUS;
  bitacoraContactos($_ID,'Se ajusta relación '.$contact_type);
  sleep(1);
}




if ($_POST["function"] == "update_caso_code") {
  $value = $mysqli2->real_escape_string($_POST["value"]);
  $UPDATE_CASO_CODE = "UPDATE casos set code ='" . $value . "' where caso_id=" . $_ID;
  $mysqli2->real_query($UPDATE_CASO_CODE);
  bitacoraUpdateCaso($_ID, $value);
  echo empty($value)  ? "Vacio!" : $value;
}
if ($_POST["function"] == "update_caso_cuantia") {
  $value = $mysqli2->real_escape_string($_POST["value"]);
  $conv = array("$" => "", "," => "");
  $cuantia = strtr($value, $conv);
  $UPDATE_CASO_CODE = "UPDATE casos set cuantia ='" . $cuantia . "' where caso_id=" . $_ID;
  $mysqli2->real_query($UPDATE_CASO_CODE);
  bitacoraUpdateCaso($_ID, $cuantia);
  echo empty($cuantia)  ? "Vacio!" : "$" . number_format($cuantia, 0);
}

if ($_POST["function"] == "update_caso_name") {
  $value = $mysqli2->real_escape_string($_POST["value"]);
  $UPDATE_CASO_NAME = "UPDATE casos set name ='" . $value . "' where caso_id=" . $_ID;
  $mysqli2->real_query($UPDATE_CASO_NAME);
  bitacoraUpdateCaso($_ID, $value);
  echo empty($value)  ? "Vacio!" : $value;
}


if ($_POST["function"] == "create_add_client") {

  $ADD_CONTACT =    "INSERT INTO contacts " .
    "(contact_id, name,status,created_by, datalog) " .
    "VALUES 
    (" ."null,'" . $_POST["contact_name_new"] . "','1','" . $_SESSION["auth_id"] . "',CURRENT_TIMESTAMP);";
  $mysqli->real_query($ADD_CONTACT);
  $new_contact_id = $mysqli->insert_id;

  $query = "INSERT INTO casos_contacts ( caso_id, contact_id, contact_type) values('" . $_ID . "','" . $new_contact_id . "','1' );  ";
  $mysqli->real_query($query);
  bitacoraContactos($_ID,$_POST["contact_name_new"]);

  ?>
  <div class="row mr-1 ml-1 font-weight-bold d-none d-lg-flex   ">
    <div class="col-md-1"></div>
    <div class="col-md-3">NOMBRE</div>
    <div class="col-md-2">TELEFONO</div>
    <div class="col-md-2">EMAIL</div>
    <div class="col-md-2">NIT</div>
    <div class="col-md-2">RELACIÒN</div>
  </div>
<?php
  $SQL_CONTACTS = "SELECT * FROM casos_contacts join contacts on casos_contacts.contact_id=contacts.contact_id where caso_id=" . $_ID;

  $mysqli2->real_query($SQL_CONTACTS);
  $contacts = $mysqli2->use_result();

  while ($u = $contacts->fetch_assoc()) {

    if (!empty($u["email"])) {
      $email = '<a href="mailto:' . $u["email"] . '">' . $u["email"] . '</a>';
      $email_icon = ' <i class="fa fa-envelope-o fa-2x mt-2 guestaccess"  aria-hidden="true" contact-id="' . rawurlencode(base64_encode(auyama_encrypt($u["contact_id"]))) . '"></i> ';
    } else {
      $email = "";
      $email_icon = "";
    }
    echo  '<div class="row mr-1 ml-1">' .
      '<div class="col-md-2" data-toggle="tooltip" data-placement="top" title="Muestra la tarjeta del contacto">' .
      '<i class="fa fa-trash fa-2x mt-2 delete-contact" aria-hidden="true" contact-id="' . rawurlencode(base64_encode(auyama_encrypt($u["contact_id"]))) . '"></i> ' .
      '<a href="view_contact.php?id=' . rawurlencode(base64_encode(auyama_encrypt($u["contact_id"]))) . '">' .
      '<i class="fa fa-address-book fa-2x mt-2" aria-hidden="true"></i>' .
      '</a>' .
      $email_icon .
      '</div>' .
      '<div class="col-md-2">' . $u["name"] . '</div>' .
      '<div class="col-md-2">' . $u["phone"] . '</div>' .
      '<div class="col-md-2 text-truncate">' . $email . '</div>' .
      '<div class="col-md-2">' . $u["nit"] . '</div>' .
      '<div class="col-md-2">' . $key[$u["contact_type"]] . '</div>' .
      '</div>';
  }
  sleep(1);
}


?>
<?php
#error_reporting(E_ALL);
ini_set('display_errors',0);
/*
Usuario Database Mysql:
Database: auyamawa_nube24
User: auyamawa_nube24
Password: Lb3uz16_UB24

Software para acceso al Database:

https://server3.hostingplan.pro:8443/domains/databases/phpMyAdmin/

https://blueimp.github.io/jQuery-File-Upload/angularjs.html

https://www.devbridge.com/sourcery/components/jquery-autocomplete/
*/

 
$host = 'localhost';
$user = 'offimovil.com';
$pass = '5m90r#Mt';
$name = 'nube24amarillo';

$mysqli = new mysqli($host, $user, $pass, $name);
$mysqli2 = new mysqli($host, $user, $pass, $name);
#$mysqli3 = new mysqli($host, $user, $pass, $name);
//$DOCUMENT_FILE_DIRECTORY="/var/www/vhosts/noip.it/httpdocs/nube24/download/";
$DOCUMENT_FILE_DIRECTORY="https://www.offimovil.com/movil/download";
$DOCUMENT_FILE_PATH="/movil/download/";
$TITULO = "My office";
 if ($mysqli->connect_errno) {
    $sqlerrors[1]= "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
 
$email_from="hernandoperezgalvis@offimovil.com";

$email_to="hernandoperezgalvis@offimovil.com";
$email_name="My office";

$email_bcc="acrcartera2014@gmail.com";
$name_bcc="Hernando Perez Galvis";


$smtp_host="localhost";
$smtp_user="hernandoperezgalvis@offimovil.com";
$smtp_pass='f*82Yh7h';
$port=25;
$secure="tls";

$option_type= array(
    1=>'fa-sort-numeric-asc',
    2=>'fa-calendar',
    3=>'fa-money',
    4=>'fa-edit',
    5=>'fa-id-card-o',
    6=>'fa-university',
    7=>'fa-trophy',
);

function auyama_encrypt($data){

    $initialVector ="XXyamira12345678";
    $secretKey = 	"Myamira123456789"; // string : 16 length
    return  openssl_encrypt($data, "aes-128-cbc", $secretKey, OPENSSL_RAW_DATA, $initialVector);
}


function auyama_decrypt($data){

    $initialVector = "XXyamira12345678";
    $secretKey = "Myamira123456789"; // string : 16 length
    return  openssl_decrypt($data, "aes-128-cbc", $secretKey, OPENSSL_RAW_DATA, $initialVector);
}

 

$province = array(
    'AG' => 'Agrigento',
    'AL' => 'Alessandria',
    'AN' => 'Ancona',
    'AO' => 'Aosta',
    'AR' => 'Arezzo',
    'AP' => 'Ascoli Piceno',
    'AT' => 'Asti',
    'AV' => 'Avellino',
    'BA' => 'Bari',
    'BT' => 'Barletta-Andria-Trani',
    'BL' => 'Belluno',
    'BN' => 'Benevento',
    'BG' => 'Bergamo',
    'BI' => 'Biella',
    'BO' => 'Bologna',
    'BZ' => 'Bolzano',
    'BS' => 'Brescia',
    'BR' => 'Brindisi',
    'CA' => 'Cagliari',
    'CL' => 'Caltanissetta',
    'CB' => 'Campobasso',
    'CI' => 'Carbonia-Iglesias',
    'CE' => 'Caserta',
    'CT' => 'Catania',
    'CZ' => 'Catanzaro',
    'CH' => 'Chieti',
    'CO' => 'Como',
    'CS' => 'Cosenza',
    'CR' => 'Cremona',
    'KR' => 'Crotone',
    'CN' => 'Cuneo',
    'EN' => 'Enna',
    'FM' => 'Fermo',
    'FE' => 'Ferrara',
    'FI' => 'Firenze',
    'FG' => 'Foggia',
    'FC' => 'Forlì-Cesena',
    'FR' => 'Frosinone',
    'GE' => 'Genova',
    'GO' => 'Gorizia',
    'GR' => 'Grosseto',
    'IM' => 'Imperia',
    'IS' => 'Isernia',
    'SP' => 'La Spezia',
    'AQ' => 'L\'Aquila',
    'LT' => 'Latina',
    'LE' => 'Lecce',
    'LC' => 'Lecco',
    'LI' => 'Livorno',
    'LO' => 'Lodi',
    'LU' => 'Lucca',
    'MC' => 'Macerata',
    'MN' => 'Mantova',
    'MS' => 'Massa-Carrara',
    'MT' => 'Matera',
    'ME' => 'Messina',
    'MI' => 'Milano',
    'MO' => 'Modena',
    'MB' => 'Monza e della Brianza',
    'NA' => 'Napoli',
    'NO' => 'Novara',
    'NU' => 'Nuoro',
    'OT' => 'Olbia-Tempio',
    'OR' => 'Oristano',
    'PD' => 'Padova',
    'PA' => 'Palermo',
    'PR' => 'Parma',
    'PV' => 'Pavia',
    'PG' => 'Perugia',
    'PU' => 'Pesaro e Urbino',
    'PE' => 'Pescara',
    'PC' => 'Piacenza',
    'PI' => 'Pisa',
    'PT' => 'Pistoia',
    'PN' => 'Pordenone',
    'PZ' => 'Potenza',
    'PO' => 'Prato',
    'RG' => 'Ragusa',
    'RA' => 'Ravenna',
    'RC' => 'Reggio Calabria',
    'RE' => 'Reggio Emilia',
    'RI' => 'Rieti',
    'RN' => 'Rimini',
    'RM' => 'Roma',
    'RO' => 'Rovigo',
    'SA' => 'Salerno',
    'VS' => 'Medio Campidano',
    'SS' => 'Sassari',
    'SV' => 'Savona',
    'SI' => 'Siena',
    'SR' => 'Siracusa',
    'SO' => 'Sondrio',
    'TA' => 'Taranto',
    'TE' => 'Teramo',
    'TR' => 'Terni',
    'TO' => 'Torino',
    'OG' => 'Ogliastra',
    'TP' => 'Trapani',
    'TN' => 'Trento',
    'TV' => 'Treviso',
    'TS' => 'Trieste',
    'UD' => 'Udine',
    'VA' => 'Varese',
    'VE' => 'Venezia',
    'VB' => 'Verbano-Cusio-Ossola',
    'VC' => 'Vercelli',
    'VR' => 'Verona',
    'VV' => 'Vibo Valentia',
    'VI' => 'Vicenza',
    'VT' => 'Viterbo',
);
global $MSG;
$MSG = array(
    "1"=>"login efectuado",
    "2"=>"User name/password errados",
    "3"=>"El usuario fue creado correctamente",
    "4"=>"Las modificas han sido guardadas correctamente",
    "5"=>"Cambio password",
    "6"=>"Cambio configuracciones",
    "7"=>"Invitacion enviada",
    "8"=>"Cancelacion del recurso/por parte del usuario: ",
    "9"=>"Cancelacion del evento/por parte del usuario: ",
    "10"=>"Evento creado por parte del usuario: ",
    "11"=>"Evento modificado por parte del usuario: ",
    "12"=>"Tentativo de cancelacion evento/por parte del usuario: "
);
$STATUS_CASO ="Esta opci&ograve;n le servir&agrave; para asignar estados a un caso, debe colocar los nombres con un numero, debe colocar dos puntos seguido del numero y coloque la coma en caso de que sean varios estados, ejemplo: 1: Nuevo, 2: Inicio, 3: Pendiente, 4: Terminado, 5: Revisado, 6: Cobro pendiente, 7: Aplazado  (entre otros).";
$CONTACTS_TYPE="Esta opci&ograve;n le servir&agrave; para indicar que tipo de interacci&ograve;n tiene un contacto con un campo, debe colocar los nombres con un numero, debe colocar dos puntos seguido del numero y coloque la coma en caso de que sean varios contactos, ejemplo: 1: Estudiante, 2: Profesor, 3: Abogado, 4: Juzgado, 5: Paciente, 6: Psicologo, 7: Empleado, 8: Empleador, 9: Servicio publico  (entre otros).";
function ydhatta(){
    echo    '<div class="row mb-2  rounded"><div class="col-md-12 m-3 p-3">'.
                '<div class="alert alert-danger text-center" role="alert">'.
                '<i class="fa fa-exclamation-triangle fa-2x" aria-hidden="true"></i> '.
                'No tiene permiso de acceder a esta area, si usted cree que es un error, por favor contacte el administrador.'.
                '</div>'.
            '</div></div>';
}

function login($section ,$msg){
    Global $_SESSION,$mysqli2;

    $SQL_LOGIN = "INSERT INTO log (log,user_id,section,ip,msg) VALUES (NULL, '".$_SESSION["auth_id"]."','".$section."','".$_SERVER['REMOTE_ADDR']."','".$msg."' )";
    $mysqli2->real_query($SQL_LOGIN);
    #echo  $SQL_LOGIN ;
}
function randomPassword($len = 8){
 
	if(($len%2)!==0){ 
		$len=8;
	}
	$length=$len-2;  
	$conso=array('b','c','d','f','g','h','j','k','l','m','n','p','r','s','t','v','w','x','y','z');
	$vocal=array('a','e','i','o','u');
	$password='';
	srand ((double)microtime()*1000000);
	$max = $length/2;
	for($i=1; $i<=$max; $i++){
		$password.=$conso[rand(0,19)];
		$password.=$vocal[rand(0,4)];
	}
	$password.=rand(10,99);
	$newpass = $password;
	return $newpass;
}
?>


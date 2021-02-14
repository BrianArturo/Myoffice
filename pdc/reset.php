<?php
include("inc/config.php");
$QUERY_SETTINGS="
TRUNCATE TABLE  events; ALTER TABLE events AUTO_INCREMENT = 0;
TRUNCATE TABLE  casos; ALTER TABLE casos AUTO_INCREMENT = 0;
TRUNCATE TABLE 	casos_options;  ALTER TABLE casos_options AUTO_INCREMENT = 0;
TRUNCATE TABLE  contacts; ALTER TABLE contacts AUTO_INCREMENT = 0;
TRUNCATE TABLE  log; ALTER TABLE log AUTO_INCREMENT = 0;
TRUNCATE TABLE  contacts; ALTER TABLE contacts AUTO_INCREMENT = 0;
TRUNCATE TABLE  notes; ALTER TABLE notes AUTO_INCREMENT = 0;
DELETE FROM users WHERE id > 2; ALTER TABLE users AUTO_INCREMENT = 5;
TRUNCATE TABLE 	casos_contacts;  
TRUNCATE TABLE  casos_notes;  
TRUNCATE TABLE  user_casos;  
TRUNCATE TABLE  user_setting;
UPDATE users set password='f8bb0854fe117e143d6ef2a61bacd09d' where id=1;  
";
 
if (!$mysqli->multi_query($QUERY_SETTINGS)) {
    echo "Multi query failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
?>
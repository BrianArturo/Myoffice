<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include("inc/config.php");
session_start();
if (!isset($_SESSION["auth_id"]) ){ header ("Location:index.php"); }
if($_GET['logout']=="logout"){ unset($_SESSION); }



$QUERY_SETTINGS="SELECT * FROM settings where owned_by = '".$_SESSION["auth_id"]."'or owned_by = '".$_SESSION["created_by"]."'";
$mysqli->real_query($QUERY_SETTINGS);
$result = $mysqli->use_result();

while ($row = $result->fetch_assoc()) {
  $s[$row["name"]]=$row["value"];
  #echo $row["name"]." - > ".$row["value"]."<br>";
}
$contacts_type = explode(",",$s["contacts_type"]);
foreach($contacts_type as $c){
  $contacts_type_key = explode(":",$c);
  #echo $contacts_type_key[0]." ->> ".$contacts_type_key[1]."<br>";
  $contact_type[$contacts_type_key[0]]=$contacts_type_key[1];
}
$status_caso = explode(",",$s["status_caso"]);
foreach($status_caso as $n){
  $status_caso_key = explode(":",$n);
  #echo $status_caso_key[0]." ->> ".$status_caso_key[1]."<br>";
  $status_caso_array[$status_caso_key[0]] = $status_caso_key[1] ;
  #$status_caso_array[$status_caso_key[0]] ="".$status_caso_key[1]."";
}
if($_SESSION["type"]=="user" || $_SESSION["type"]=="guest")
{
  $STATS_CASOS="SELECT DISTINCT status,count(status) as quantita FROM casos where status>0 and caso_id in (SELECT caso_id FROM user_casos where user_id ='".$_SESSION["auth_id"]."' )  GROUP BY status";
}
else{
  $STATS_CASOS="SELECT DISTINCT status,count(status) as quantita FROM casos where status>0 and created_by='".$_SESSION["auth_id"]."'  GROUP BY status";
}
//var_dump($STATS_CASOS);
$mysqli->real_query($STATS_CASOS);
//var_dump($mysqli);exit();
//var_dump($_SESSION);
$result = $mysqli->use_result();

while ($r = $result->fetch_assoc()) {
    ++$count;
  $values[$r["status"]]=$r["quantita"];
  $status_caso_array_name[]="'".$status_caso_array[$r["status"]]."'";
  $status_caso_array_values[]=$r["quantita"];
  #echo $status_caso_array[$r["status"]]." - > ".$r["quantita"]." (".$count.")<br>";
}
//var_dump($status_caso_array_values);
?>
<!DOCTYPE HTML>
    <head>
        <title><?php echo $TITULO ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<script src="js/Chart.min.js"></script>

		<?php include("inc/header.php"); ?>


    </head>
    <body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

 

        <?php include("inc/menu.php"); ?>
		<div class="container-fluid" style="max-width:1450px;margin-top:20px;">
    		<div id="content" class="wrapper">
				<h3>Tu estadistica</h3>
			</div>
		</div>

            <div class="container-fluid mb-5" style="max-width:1450px; ">
    			<div id="content" class="wrapper">

 <!-- 
https://www.chartjs.org/docs/latest/general/colors.html
 -->

 <div class="row ">
		<div class="col-md-8 mt-3 pt-3 mr-auto ml-auto"><canvas id="CasosEstados" ></canvas></div>
    <div id="chartjs-legend" class="noselect"></div>
</div>
<?php 
if( $count > 2){
?>
<script>
  
var ctx = document.getElementById('CasosEstados');
//var cty = document.getElementById('CasosEstados2');
var myChart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: [<?php echo join(",",$status_caso_array_name); ?>],
        datasets: [{
            label: 'Interacciones diarias',
            data: [<?php echo join(",",$status_caso_array_values); ?>],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 2
        }]
    },
    options:
    {
      responsive:true,
      legend:
      {
        display:false,
      },
      legendCallback: function(chart) {
    var text = [];
    text.push('<ul class="' + chart.id + '-legend">');
    for (var i = 0; i < chart.data.datasets[0].data.length; i++) {
      text.push('<li><span style="background-color:' + chart.data.datasets[0].backgroundColor[i] + '">');
      if (chart.data.labels[i]) {
        text.push(chart.data.labels[i]);
      }
      text.push('</span></li>');
    }
    text.push('</ul>');
    return text.join("");
  },
    }
});
$("#chartjs-legend").html(myChart.generateLegend());

ctx.onclick = function(e) {
   var activePoints = myChart.getElementAtEvent(e);
    
   if (!activePoints.length) return; // return if not clicked on slice
 
    var clickedElementindex = activePoints[0]["_index"];
    var label = myChart.data.labels[clickedElementindex];
    var value = myChart.data.datasets[0].data[clickedElementindex];

      //alert("Pregunta para hernando, te muestro todos los casos en estado: "+label+ "? ");
      location.href='casos.php?status='+label;
     
}
</script>
<?php
}else{
?>
<div class="alert alert-primary" role="alert"><i class="fa fa-exclamation-triangle fa-2x" aria-hidden="true"></i> No tenemos datos suficientes para las estadisticas</div>
<?php
}
?>

				</div>
    		</div>

        <?php
		if($_SESSION["type"] !="guest"){
			include("inc/footer.php");	
		}?>
    <!--<script type="text/javascript" src="js/script.js"></script>-->
    <script src="https://code.createjs.com/easeljs-0.8.2.min.js"></script>
    </body>
</html>

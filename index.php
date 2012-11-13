<?php
include "src/subaru.php";

$sudaru = new subaru();

?>
<!doctype html public>
<!--[if lt IE 7]> <html lang="en-us" class="lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>    <html lang="en-us" class="lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>    <html lang="en-us" class="lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en-us"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <title>SUBARU</title>
  <meta name="viewport" content="width=device-width">
  	<link rel="stylesheet" type="text/css" href="css/main.css"/>

  	<!-- theme para validacion -->
  	<link rel="stylesheet" href="css/validationEngine.jquery.css" type="text/css"/>
	<link rel="stylesheet" href="css/template.css" type="text/css"/>

  	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
	
	<!-- validacion -->
	<script type="text/javascript" src="js/languages/jquery.validationEngine-es.js"></script>
  	<script type="text/javascript" src="js/jquery.validationEngine.js"></script>

  	<!-- main -->
  	<script type="text/javascript" src="js/main.js"></script>

</head>
<body>
<div class="form_page" id="content">
	<h2 class="world_rally">WORLD RALLY CHAMPIONSHIP</h2>
	<p class="subaru_logo"><a href="#">SUBARU</a></p>

	<div class="form_section">
		<h2>Datos de registro</h2>
		<form id="registro" onsubmit="return false">
			<input type="text" name="nombre" id="nombre" placeholder="Name" class="validate[required]">
			<input type="text" name="cedula" id="cedula" placeholder="Cedula" class="validate[required,custom[integer]]">
			<input type="text" name="correo" id="correo" placeholder="Correo" class="validate[required,custom[email]]">
			<input type="text" name="fecha_nacimiento" id="fecha_nacimiento" placeholder="Fecha de nacimiento" class="validate[required,custom[date]]">
			<button id="submit" class="submit"></button>
		</form>
	</div>

</div>
</body>
</html>

<?php
require 'facebook/facebook.php';
$facebook = new Facebook(array(
		'appId'  => '287972814647780',
		'secret' => "8f720272f8395d4a76e384e950834036",
		'cookie' => true,
));

$user = $facebook->getUser();
$signed_request = $facebook->getSignedRequest();
$uid = $signed_request["user_id"];

$like_status = $signed_request["page"]["liked"];

if($like_status != 1){
	?>
	no le ha dado like2
	<?php
}else{
	?>
	si
<?php 
}

?>
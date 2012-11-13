<?php

include('src/subaru.php');

$subaru = new Subaru();

echo 'datos: <br/>'.
print_r($subaru -> getDatos());


/*
//parametro de la app paa facebook
$facebook = new Facebook(array(
   'appId' => '533752239970800',
   'secret' => '3514c9cc9d9dab2606330ac6211ddae2',
   'cookie' => true,
));
	
// Obtener el ID del Usuario
$user = $facebook->getUser();


if ($user) {
	try {
	    // errores se guardan en un archivo de texto (error_log)
		$user_profile = $facebook->api('/me');
	} catch (FacebookApiException $e) {
		error_log($e);
		$user = null;
	} 
}
 

//no logueado -> pagina de logueo
if ( !$user ) {
	echo 'No logueado';

	$_SESSION['logueado'] = false;

	//prmisos solicitados para la app
	$loginUrl = $facebook->getLoginUrl(array('scope' => 'email,publish_stream'));

	if(!$_SESSION['logueado']){	
		echo "<script type='text/javascript'>top.location.href = '".$loginUrl."';</script>";		
	}

}else{
	//logueado
	echo 'Logueado';
	$_SESSION['logueado'] = true;
}

//datos del usuario

echo '<br/>datos: '.$user_profile['name'].' <br/>mail: '.$user_profile['email'];
echo '<br/>id: '.$user_profile['id'];
*/

?>


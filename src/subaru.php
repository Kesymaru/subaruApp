<?php
require_once("facebook/facebook.php");
require_once('classes/classDatabase.php');

//Utilizar solo en modo produccion
error_reporting(E_ALL ^ E_NOTICE);
session_start();

class Subaru{
	private $datos = array();

	/*
	* Se encarga de todo lo relacionado con los permisos de facebook
	*/
	public function __construct(){
		//inicia app para facebook
		$facebook = new Facebook(array(
		   'appId' => '533752239970800',
		   'secret' => '3514c9cc9d9dab2606330ac6211ddae2',
		   'cookie' => true,
		));
			
		$user = $facebook->getUser();

		if ($user) {
			try {
				$user_profile = $facebook->api('/me');
			} catch (FacebookApiException $e) {
				error_log($e);
				$user = null;
			} 
		}

		//no logueado -> pagina de logueo
		if ( !$user ) {

			$_SESSION['logueado'] = false;

			//prmisos solicitados para la app
			$loginUrl = $facebook->getLoginUrl(array('scope' => 'email,publish_stream'));

			if(!$_SESSION['logueado']){	
				echo "<script type='text/javascript'>top.location.href = '".$loginUrl."';</script>";		
			}

		}else{
			//logueado
			$_SESSION['logueado'] = true;
			
			//datos del usuario
			$this->datos = array('nombre' => $user_profile['name'] , 'correo' => $user_profile['email'], 'id' => $user_profile['id'] );
		}

	}

	/*
	* devuelve datos usario
	* return datos array
	*/
	public function getDatos(){
		return $this->datos;
	}

	/*
	* REALIZA REGISTRO DE NUEVO PARTICIPANTE
	* @param $nombre -> string del nombre
	* @param $correo -> string del email
	* @param $fecha_nacimiento -> string con formato dd/mm/aaaa
	* @param $id -> id del usuario de facebook
	*/
	public function registro($nombre, $correo, $cedula, $fecha_nacimiento){
		$id = 600; //para pruebas el id es el de facebook
		$registro = new Database();
		
		$datos = array("nombre" => $nombre, "correo" => $correo, "cedula" => $cedula, "fecha_nacimiento" => $fecha_nacimiento, "id" => $id );

		//si el usuario no se ha registrado
		if( !$registro->exits('participantes', 'id', $id) ){
			
			//registra en la base de datos
			$registro->queryInsert('participantes', $datos);

			echo 'Registrdo existosamente. Invita amigos para obtener puntos.';
		}else{
			echo 'Ya te has registrado, Invita amigos para obtener puntos.';
		}
		
	}
}


$subaru = new Subaru();
?>
<?php
require_once("facebook/facebook.php");
require_once('classes/classDatabase.php');

//Utilizar solo en modo produccion
//error_reporting(E_ALL ^ E_NOTICE);
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
			/*
			$_SESSION['logueado'] = false;

			//prmisos solicitados para la app
			$loginUrl = $facebook->getLoginUrl(array('scope' => 'email,publish_stream'));

			if(!$_SESSION['logueado']){	
				echo "<script type='text/javascript'>top.location.href = '".$loginUrl."';</script>";		
			}
			*/

			//prmisos solicitados para la app
			$loginUrl = $facebook->getLoginUrl(array('scope' => 'email,publish_stream'));
			$loginUrl .= '&redirect_uri=https://www.facebook.com/pages/77-Leo-Burnett/111431072353847?sk=app_533752239970800';
			
			echo "<script type='text/javascript'>top.location.href = '".$loginUrl."';</script>";		
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

	public function getId(){
		return $this->datos['id'];
	}

	/*
	* REALIZA REGISTRO DE NUEVO PARTICIPANTE
	* @param $nombre -> string del nombre
	* @param $correo -> string del email
	* @param $fecha_nacimiento -> string con formato dd/mm/aaaa
	* @param $id -> id del usuario de facebook
	*/
	public function registro($nombre, $correo, $cedula, $fecha_nacimiento, $id){
		$registro = new Database();
		
		$datos = array("nombre" => $nombre, "correo" => $correo, "cedula" => $cedula, "fecha_nacimiento" => $fecha_nacimiento, "id" => $id );

		//si el usuario no se ha registrado
		$registro->exits('participantes', 'id', $id);
		
		if( $registro->getExiste() == 'no existe' ){
			
			//registra en la base de datos
			$registro->queryInsert('participantes', $datos);

			//puntos por defecto
			$default_puntos = array('participante' => $this->datos['id'] , 'putos' => 0 );
			$registro->queryInsert('puntos', $default_puntos);

		}else{
			echo 'Error 1: Usuario ya registrado.';
		}
		
	}

	/**
	* REALIZA EL REGISTRO DE REFERENCIAS DE UN USURIO
	* @param referencias -> array con id de usuarios
	*/
	public function referencias($referencias){
		$registro = new Database();

		//print_r($referencias);

		foreach($referencias as $key => $value) {
			$dato = array('participante' => $this->datos['id'], 'referencia' => $value );
			$registro->queryInsert('referencias', $dato);
		}

		//calcula los puntos de los participantes
		$registro->puntos();
	}

	/**
	* REVISA SI LE DIO LIKE A LA FAN PAGE
	*/
	public function like(){
		$facebook = new Facebook(array(
		   'appId' => '533752239970800',
		   'secret' => '3514c9cc9d9dab2606330ac6211ddae2',
		   'cookie' => true,
		));

		$user = $facebook->getUser();
		$signed_request = $facebook->getSignedRequest();
		$uid = $signed_request["user_id"];
		$like_status = $signed_request["page"]["liked"];
		
		if($like_status != 1){
			//muestra pagina de like
			echo '<script>like();</script>';
		}else{
			echo '<script>participar();</script>';
		}
	}
}

?>
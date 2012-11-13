<?php

require_once('classes/classDatabase.php');

//Utilizar solo en modo produccion
error_reporting(E_ALL ^ E_NOTICE);
session_start();

class Subaru{
	public function __construct(){

	}

	//realiza la peticion de permisos y obtencion de datos del usuario
	public function permisos(){

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

?>
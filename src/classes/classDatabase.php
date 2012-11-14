<?php
/*
 * ACORE 4.0 Author: Brian Salazar http://www.avenidanet.com
 * 
 * Clase de Abstracion de la base de datos
 */
class Database{
	
	private $dbHost 	= "localhost";
	private $dbUser 	= "SubaruMexico";
	private $dbPassword = "Suba159!!";
	private $dbDatabase = "subarumexico";
	
	private $dbLink      = 0;
	private $dbRecordSet = 0;
	public  $dbResult    = false;
	public $exite        = false;


/* Metodos principales */

	public function __construct(){
		//verificar configuracion
			
			//Conectar 
			$this->conect();
					
			if($this->dbDatabase != ""){
				$this->setBase();
			}
	}
	
	//Conexion
	private function conect(){
		$this->dbLink= mysql_connect($this->dbHost, $this->dbUser, $this->dbPassword) or die ("1. No funciona por " . mysql_error()); 
	}
	//Seleccionar base	
	private function setBase(){
		mysql_select_db($this->dbDatabase) or die ("2. No funciona por " . mysql_error()); 
	}
		
	public function __destruct(){
		$this->disconnect();	
	}
	//Desconexion
	private function disconnect(){
		if($this->dbLink){
		mysql_close($this->dbLink);	
		}
	}
	//Ejecuta consulta
	private function query($query){
		
		$resultado = mysql_query($query) or die ("3. No funciona por " . mysql_error());
		
		if(is_bool($resultado)){
			$this->dbResult = $resultado;
		}else{
			$this->dbRecordSet = $resultado;
		}
	}	
	//Devuelve numero de filas del recordset
	public function getRows(){
		return mysql_num_rows($this->dbRecordSet);
	}

	//Devuelve el recordSet en un arreglo
	public function getRecordSet(){
		$registros = array();
		if($this->getRows()){
			while($registro = mysql_fetch_assoc($this->dbRecordSet)){
				$registros[]=$registro;
			}
		}
		return $registros;
	}
		
	
/* Manejo de consultas */

	//Sentencia SELECT
	public function querySelect($tabla,$tipo = "default",$campos = "*",$where = "", $order = "",$limit = ""){
		
		$sentencia = "SELECT ";
		
		$sentencia .= ( $tipo == 'count' ) ? 'COUNT(' : '';
		
			if($campos != "*"){
				foreach($campos as $campos => $valor){
					$sentencia .= $valor . ",";
				}
				$sentencia = substr($sentencia, 0, -1);
			}else{
				$sentencia .= $campos;
			}
		
		$sentencia .= ( $tipo == 'count' ) ? ')' : '';
		
		$sentencia .= " FROM " . $tabla;
		$sentencia .= ( $where == '' ) ? '' : ' WHERE ' . $where;
		$sentencia .= ( $order == '' ) ? '' : ' ORDER BY ' . $order;
		$sentencia .= ( $limit == '' ) ? '' : ' LIMIT ' . $limit;
		$sentencia .= ";";
		//echo $sentencia;
		$this->query($sentencia);
	}		
	
	//Sentencia INSERT
	public function queryInsert($tabla,$datos){
	
        $campos  = "";
        $valores = "";
        
        foreach ($datos as $field => $value)
        {
            $campos 	.= "".$field.",";
            $valores 	.= ( is_numeric( $value )) ? $value."," : "'".$value."',";			
        }
		
		$campos 	= substr($campos, 0, -1);
        $valores 	= substr($valores, 0, -1);
		
        $sentencia = "INSERT INTO " . $tabla ."(".$campos.") VALUES( ".$valores.");";
		
		//echo $sentencia;
		$this->query($sentencia);
	}

	/**
	* TRUNCA UNA TABLA
	* @param $tabla -> tabla ha limpiar
	*/
	public function clear($tabla){
		$query = "TRUNCATE TABLE ".$tabla;
		mysql_query($query) or die('4. No funciona por '. mysql_errno());
	}
	
	/**
	* REVISA SI EXISTE UN DATO DENTRO DE UNA TABLA
	* return true -> si existe
	* param $tabla -> tabla ha consultar
	* param $campo -> campo ha seleccionar
	* param $id -> valor ha comparar
	*/
	public function exits($tabla, $campo, $id){
		$query = "SELECT ".$campo." FROM ".$tabla." WHERE ".$campo." = ".$id;

		$resultado = mysql_query($query) or die ("5. No funciona por " . mysql_error());
		
		if($resultado = mysql_fetch_array($resultado)){
			$this->existe = 'si existe';
		}else{
			$this->existe = 'no existe';
		}
	}

	/**
	* GETTER PARA EXISTE
	* return existe
	*/
	public function getExiste(){
		return $this->existe;
	}

	/**
	* CALCULA LOS PUNTOS DE TODOS LOS PARTICIPANTES
	*/
	public function puntos(){
		$query = "SELECT * FROM participantes";
		$resultado = mysql_query($query) or die( "10. No funciona por " . mysql_error());
		
		while($row = mysql_fetch_array($resultado)){
			$this->sumaPuntos($row['id']);
		}
	}

	/**
	* OBTIENE LOS PUNTOS DEL USUARIO
	* @param $id -> id del participante
	* return puntos
	*/
	private function sumaPuntos($id){
		$puntos = 0;
		$query = "SELECT * FROM referencias WHERE participante = ".$id;
		$resultado = mysql_query($query) or die ("20. No funciona por " . mysql_error());

		//suma los puntos del participante
		while($row = mysql_fetch_array($resultado)){
			
			//usuario esta invitado se registro
			/*if($this->invitado($id, $row['referencia'])){
				//cuenta
				$puntos++;
			}*/

			$this->exits('participantes', 'id', $row['referencia']);

			if($this->getExiste() == "si existe"){
				//cuenta
				$puntos++;
			}
		}

		if($puntos != 0){
			$query = "UPDATE puntos SET putos = ".$puntos." WHERE participante = ".$id;
			mysql_query($query) or die("7. No funciona por " . mysql_error());
		}
	}

	/**
	* DETERMINA SI EL USUARIO INVITADO SE REGISTRO
	* @param $id -> id usuario quien invita
	* @param $referencia -> id usuario invitado
	* return true -> si fue invitado
	*/
	private function invitado($id, $referencia){
		$query = "SELECT * FROM participantes WHERE id = ".$id;

		$resultado = mysql_query($query) or die ("6. No funciona por " . mysql_error());
		
		if($resultado = mysql_fetch_array($resultado)){
			return true;
		}else{
			return false;
		}
	}

}
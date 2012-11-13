<?php
/*
 * ACORE 4.0 Author: Brian Salazar http://www.avenidanet.com
 * 
 * Clase de Abstracion de la base de datos
 */
class Database{
	
	private $dbHost 	= "localhost";
	private $dbUser 	= "root";
	private $dbPassword = "";
	private $dbDatabase = "subaru";
	
	private $dbLink 	= 0;
	private $dbRecordSet = 0;
	public  $dbResult 	= false;


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
		//$this->dbLink= mysql_connect($this->dbHost, $this->dbUser, $this->dbPassword) or die ("1. No funciona por " . mysql_error()); 

		$this->dbLink= mysql_connect($this->dbHost, $this->dbUser) or die ("1. No funciona por " . mysql_error()); 

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

/* CONSULTAS PREDEFINIDAS */
	
	/*
	* revisa si existe un dato dentro de una tabla
	* return true -> si existe
	* param $tabla -> tabla ha consultar
	* param $campo -> campo ha seleccionar
	* param $valor -> valor ha comparar
	*/
	public function exits($tabla, $campo, $valor){
		$query = "SELECT ".$campo." FROM ".$tabla." WHERE ".$campo." = ".$valor;

		$resultado = mysql_query($query) or die ("4. No funciona por " . mysql_error());
		
		if($resultado = mysql_fetch_array($resultado)){
			return true; //existe
		}else{
			return false; //no existe
		}
	}

	/*
	* cuenta los amigos referenciados por el participante 
	* param $id -> id del participante
	*/
	public function sumaPunto($id){
		$this->query('SELECT * FROM puntos WHERE id = '.$id);
		echo $this->dbResult;
	}
}
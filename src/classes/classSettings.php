<?php
/*
 * ACORE 4.0 Author: Brian Salazar http://www.avenidanet.com
 * 
 * Modo de uso
 * $settings = Settings::Init();
 * $settings->nombre = 'federico';
 * echo $settings->nombre;
 */

class Settings{
    
	private $vars = array();
    private static $instance = null;

    private function __construct(){
    }
    //Una sola instancia
    public static function init()
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }
 		return self::$instance;
    }
 
    public function __get($name) {
		if(isset($this->vars[$name])){
    		return $this->vars[$name];
		} else {
			echo "Variable no definida";
		}
    }
    
    public function __set($name, $value) {
    	$this->vars[$name] = $value;
    }
}
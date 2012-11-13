<?php
include('subaru.php');
$subaru = new Subaru();

switch ($_POST['accion']) {
	case 'registro':
		if(isset($_POST['nombre']) && isset($_POST['correo']) && isset($_POST['cedula']) && isset($_POST['fecha_nacimiento'])){
			
			$subaru->registro($_POST['nombre'], $_POST['correo'], $_POST['cedula'], $_POST['fecha_nacimiento']);
		}
		break;
	
	default:
		# code...
		break;
}

?>
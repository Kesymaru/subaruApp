<?php

include('src/classes/classDatabase.php');

echo 'Datos guardados';

$base = new Database();

$base->querySelect('participantes',"default","*","","","");
$datos = $base->getRecordSet();

echo 'Participantes<br/><pre>';
print_r($datos);
echo '</pre>';

$base->querySelect('referencias',"default","*","","","");
$datos = $base->getRecordSet();

echo 'Referencias<br/><pre>';
print_r($datos);
echo '</pre>';

$base->querySelect('puntos',"default","*","","","");
$datos = $base->getRecordSet();

echo 'Puntos<br/><pre>';
print_r($datos);
echo '</pre>';

if(isset($_GET['pa'])){
	$base->clear('participantes');
	$base->clear('referencias');
	$base->clear('puntos');
}


?>
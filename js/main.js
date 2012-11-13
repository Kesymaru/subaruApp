$(document).ready(function (){

	//inicia validacion
	$('#registro').validationEngine();

	$('#submit').click(function (){
		sendForm();
	});
});

//envia formulario
function sendForm(){
	//los datos son validos
	if ( $('#registro').validationEngine('validate') ){
		alert('datos validos');
	}else{
		
	}

}
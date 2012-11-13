
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
		var nombre = $('#nombre').val();
		var cedula = $('#cedula').val();
		var correo = $('#correo').val();
		var fecha_nacimiento = $('#fecha_nacimiento').val();
		
		//alert(nombre+cedula+correo+fecha_nacimiento);
		var queryParams = {'accion' : 'registro', 'nombre' : nombre, 'cedula' : cedula, 'correo' : correo, 'fecha_nacimiento' : fecha_nacimiento };
		$.ajax({
			data: queryParams,
			url: 'src/ajax.php',
			type: 'post',
			success: function (response){
				alert(response);
			}
		});
	}	

}

$(document).ready(function (){

	$('#formulario').hide();

	//inicia validacion
	$('#registro').validationEngine();

	$('#submit').click(function (){
		sendForm();
	});

	//inicializa facebook
	FB.init({
	    appId  : '533752239970800',
	    frictionlessRequests: true,
	    status: true,
        cookie: true,
        oauth: true
	});
	
});

/**
* ENVIA FORMULARIO Y REALIZA REGISTRO
*/
function sendForm(){
	//los datos son validos
	if ( $('#registro').validationEngine('validate') ){
		var nombre = $('#nombre').val();
		var cedula = $('#cedula').val();
		var correo = $('#correo').val();
		var fecha_nacimiento = $('#fecha_nacimiento').val();
		
		var queryParams = {'accion' : 'registro', 'nombre' : nombre, 'cedula' : cedula, 'correo' : correo, 'fecha_nacimiento' : fecha_nacimiento };
		$.ajax({
			data: queryParams,
			url: 'src/ajax.php',
			type: 'post',
			success: function (response){

				if(response.length == 0){
					invitar();
				}else{
					notificaError('Parece que ya estas registrado.<br/>Invita a tus amigos y gana puntos.');
				}
			}
		});

	}else{
		notificaError('Debe indicar toda la informacion.');
	}	

}


/**
* FACEBOOK REQUESTS PARA MULTIPLES AMIGOS
*/
function invitar() {
	notifica('Registro Exitoso, Invita a tus amigos y gana puntos.');
    FB.ui({
    	method: 'apprequests',
    	message: 'Invita a tus amigos y obtiene puntos.',
    	title: 'Invita amigos para Subaru.',
    }, function (response){
    	 referencias(response);
    });
}
   
/**
* MANEJO DE PERSONAS INVITADAS 
* GUARDA EL ID DE LOS AMIGOS EN REFERENCIAS
* @param response
*/  
function referencias(response) {
    if (response.request && response.to) {
        var request_ids = [];

        console.log('amigos invidatos: '+response.to.length);

        for(i=0; i < response.to.length; i++) {
            request_ids[i] = response.to[i];
        }

        console.log('amigos procesados: '+request_ids.length);
		
		/**
		* Devuelve el nuemro de votos
		*/
		var queryParams = {'accion' : 'referencias', 'referencias' : request_ids}
		$.ajax({
			data: queryParams,
			async: false,
			url: 'src/ajax.php',
			type: 'post',
			success: function (response){
				//alert(response);

				if(response.length == 0){
					notifica('Exelente, entre mas amigos invites mas puntos puedes obtener.');
					thanks();
				}else{
					notificaError('Error: 2. problema al guarda tus invitaciones.');
				}
			}
		});

    } else {
        notificaError('canceled');
    }
}

/**
* CARGA LA PAGINA LIKE
*/
function like(){
	$('#carga').load('like.html');
}

/**
* CARGA LA PAGINA thanks.html
*/
function thanks(){
	$('#formulario').fadeOut();
	$('#carga').load('thanks.html');
	$('#carga').fadeIn();
}

/**
* CARGA LA PAGINA form.html
*/
function form(){
	$('#carga').hide();
	$('#formulario').fadeIn();
}

/**
* CARGA LA PAGINA DE participar.html
*/
function participar(){
	$('#carga').load('participar.html');
}

/**
* NOTIFICACIONES 
* usa el plugin noty.js
*/

//notifica
function notifica(text) {
  	var n = noty({
  		text: text,
  		type: 'alert',
    	dismissQueue: true,
  		layout: 'topLeft',
  		closeWith: ['button'], // ['click', 'button', 'hover']
  	});
  	console.log('html: '+n.options.id);
  	
  	//tiempo para desaparecerlo solo 
  	setTimeout(function (){
		n.close();
	},5000);
}

//notifica errores
function notificaError(text) {
  	var n = noty({
  		text: text,
  		type: 'error',
    	dismissQueue: true,
  		layout: 'topLeft',
  		closeWith: ['button'], // ['click', 'button', 'hover']
  	});
  	console.log('html: '+n.options.id);
  	
  	//tiempo para desaparecerlo solo 
  	setTimeout(function (){
		n.close();
	},7000);
}

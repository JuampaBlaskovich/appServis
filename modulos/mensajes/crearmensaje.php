<?php

	/**
	*
	*	Función: Crear mensaje.
	*	Método: POST.
	*
	*	Se crea un nuevo mensaje.
	*
	*/
	
	$app->post('/crearmensaje/:id_destino', function ($id_destino) use ($app) {
		
		$input = $app->request->getBody();
		
		$id_origen = $input['id_origen'];
		$asunto = $input['asunto'];		
		$mensaje = $input['mensaje'];
		
		if(empty($asunto)){
			$app->render(500,array(
				'error' => TRUE,
				'msg'   => 'Por favor, ingrese un asunto del mensaje.',
			));}

		if(empty($mensaje)){
			$app->render(500,array(
				'error' => TRUE,
				'msg'   => 'Por favor, ingrese un mensaje.',
			));}
		

		$msg = new Mensaje();		
		$msg->from 				= $id_origen;
		$msg->to 				= $id_destino;
		$msg->asunto			= $asunto;
		$msg->msj 				= $mensaje;
		$msg->save();
		
		$app->render(200);
	});
?>
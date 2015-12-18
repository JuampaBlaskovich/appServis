<?php

	/**
	*
	*	Función: Crea un anuncio.
	*	Método: POST.
	*
	*	Se crea un anuncio en base al título y descripción
	*	recibidos. Se guarda además el ID del usuario de quien
	*	crea el anuncio.
	*
	*/
	
	$app->post('/post', function () use ($app) {
		
		$input = $app->request->getBody();
		
		$title = $input['title'];		
		$userid = $input['idusuario'];
		$descripcion = $input['descripcion'];
		
		if(empty($title)){
			$app->render(500,array(
				'error' => TRUE,
				'msg'   => 'Por favor, ingrese un titulo.',
			));}

		if(empty($descripcion)){
			$app->render(500,array(
				'error' => TRUE,
				'msg'   => 'Por favor, ingrese una descripcion.',
			));}
		

		$post = new Post();		
		$post->title 		= $title;
		$post->id_usuario 	= $userid;
		$post->descripcion 	= $descripcion;
		$post->save();
		
		$app->render(200);
	});
?>
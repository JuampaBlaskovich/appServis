<?php

	/**
	*
	*	Función: Listar favoritos.
	*	Método: GET.
	*
	*	Se devuelve una lista de todos los anuncios
	*	marcados como favoritos del usuario quien solicita ($idusuario).
	*
	*/
	
	$app->get('/listarfavoritos/:idusuario', function ($idusuario) use ($app) {

		$db = $app->db->getConnection();
		
		//Se hace un JOIN de tablas.
		$publicaciones = $db->table('posts')     
						->join('favoritos', 'favoritos.id_post', '=', 'posts.id')
						->select('posts.*')
						->where('favoritos.id_usuario', '=', $idusuario)
						->get();
								
		if(empty($publicaciones)){
			$app->render(500,array(
				'error' => false,
				'msg'   => 'No tenés publicaciones Favoritas.',
		));}
		
		$app->render(200,array('data' => $publicaciones));
	});
	
?>
<?php
	
	/**
	*
	*	Funci�n: Listar anuncios.
	*	M�todo: GET.
	*
	*	Se devuelve una lista de todos los
	*	anuncios publicados.
	*
	*/
	
	$app->get('/posts', function () use ($app) {
		
		$db = $app->db->getConnection();
		
		$anuncios = $db->table('posts')->select('id', 'title', 'descripcion','created_at', 'visitas', 'categoria', 'favoritos')->orderBy('created_at', 'desc')->get();
		
		if(empty($anuncios)){
			$app->render(404,array(
				'error' => TRUE,
				'msg'   => 'A�n no hay ning�n anuncio publicado.',
			));}
		
		$app->render(200,array('data' => $anuncios));
	});
?>
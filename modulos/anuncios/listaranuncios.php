<?php
	
	/**
	*
	*	Función: Listar anuncios.
	*	Método: GET.
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
				'msg'   => 'Aún no hay ningún anuncio publicado.',
			));}
		
		$app->render(200,array('data' => $anuncios));
	});
?>
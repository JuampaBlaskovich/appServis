<?php

	/**
	*
	*	Funci�n: Borrar Anuncios.
	*	M�todo: DELETE.
	*
	*	Borrar un anuncio en base a su ID. Se borra adem�s
	*	todas las coincidencias en la tabla de 'favoritos',
	*	para que no quede en la lista de favoritos de ning�n usuario.
	*
	*/
	
	$app->delete('/borraranuncio/:id', function ($id) use ($app) {
		
		$db = $app->db->getConnection();
		
		$post = Post::find($id);
		
		if(empty($post)){
			$app->render(404,array(
				'error' => TRUE,
				'msg'   => 'Post no encontrado.',
		));}
		
		
		$borrarpost = $db->table('posts')     
						->where('id', '=', $id)
						->delete();
		
		$borrarfav = $db->table('favoritos')
						->where('id_post', '=', $id)
						-> delete();
		
		
		$app->render(200);
	});
?>
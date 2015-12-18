<?php

	$app->delete('/quitarfavorito/:id/:idusuario', function ($id, $idusuario) use ($app) {
		
		$db = $app->db->getConnection();
		
		
		$esfav = $db->table('favoritos')
					->select()
					->where('id_usuario', $idusuario)
					->where('id_post', $id)
					->get();
		
		if(empty($esfav)){
			$app->render(500,array(
				'error' => TRUE,
				'msg'   => 'Este anuncio no se encuentra en tus favoritos.',
		));}
		
		
		$dislikear = $db->table('favoritos')
						->where('id_usuario', $idusuario)
						->where('id_post', $id)
						->delete();
		
		
		$post = Post::find($id);
		
		if(empty($post)){
			$app->render(404,array(
				'error' => TRUE,
				'msg'   => 'Post no encontrado.',
		));}
		
		
		$post->favoritos--;
		$post->save();
		
		$app->render(200);
	});
?>
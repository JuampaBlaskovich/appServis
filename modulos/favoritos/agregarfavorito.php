<?php

	$app->put('/agregarfavoritos/:id/:idusuario', function ($id, $idusuario) use ($app) {
		
		$db = $app->db->getConnection();
		
		$esfav = $db->table('favoritos')
					->select()
					->where('id_usuario', $idusuario)
					->where('id_post', $id)
					->get();
		
		if(!empty($esfav)){
			$app->render(500,array(
				'error' => TRUE,
				'msg'   => 'El anuncio ya se encuentra en Favoritos.',
		));}
		
		$insertarfav = $db->table('favoritos')->insert(['id_usuario' => $idusuario, 'id_post' => $id]);
		
		$post = Post::find($id);
		
		if(empty($post)){
			$app->render(404,array(
				'error' => TRUE,
				'msg'   => 'Post no encontrado.',
		));}
			
		$post->favoritos++;
		$post->save();
		
		$app->render(200);
	});
?>
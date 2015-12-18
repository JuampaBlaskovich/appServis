<?php
	//Método: GET.
	//En vez de buscar todos los usuarios o por ID, busca todos los posts del usuario actual que está consultando.
	$app->get('/profile', function () use ($app) {

		$db = $app->db->getConnection();
		
		$posts = $db->table('posts')->select()->where('id_usuario', $user->id)->get();
		
		$app->render(200,array('data' => $posts));
	});
?>
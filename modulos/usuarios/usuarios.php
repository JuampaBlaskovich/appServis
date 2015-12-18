<?php
	//Listar los usuarios.
	$app->get('/usuarios', function () use ($app) {
		
		//Establezo conexión.
		$db = $app->db->getConnection();
		
		//Traigo información del usuario.
		$users = $db->table('usuarios')->select('id', 'name', 'email', 'auth')->get();
		
		//Devuelvo dicha información.
		$app->render(200,array('data' => $users));
	});
?>
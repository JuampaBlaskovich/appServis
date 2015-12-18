<?php
	//Listar los usuarios.
	$app->get('/usuarios', function () use ($app) {
		
		//Establezo conexi�n.
		$db = $app->db->getConnection();
		
		//Traigo informaci�n del usuario.
		$users = $db->table('usuarios')->select('id', 'name', 'email', 'auth')->get();
		
		//Devuelvo dicha informaci�n.
		$app->render(200,array('data' => $users));
	});
?>
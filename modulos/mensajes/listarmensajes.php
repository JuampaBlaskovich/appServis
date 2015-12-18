<?php

	$app->get('/listarmensajes/:idusuario', function ($idusuario) use ($app) {

		$db = $app->db->getConnection();
		
		$mensajesrecibidos 	= $db->table('mensajes') 
								->join('usuarios', 'mensajes.from', '=', 'usuarios.id')
								->select('mensajes.*', 'usuarios.name')
								->where('mensajes.to', '=', $idusuario)
								->orderBy('created_at', 'desc')
								->get();
		
		$app->render(200,array('data' => $mensajesrecibidos));
	});
	
?>
<?php
	
	$app->get('/mensaje/:id', function ($id) use ($app) {

		$db = $app->db->getConnection();
		
		$mensaje 	= $db->table('mensajes') 
						->join('usuarios', 'mensajes.from', '=', 'usuarios.id')
						->select('mensajes.*', 'usuarios.name')
						->where('mensajes.id', '=', $id)
						->get();
					
		if(empty($mensaje)){
			$app->render(404,array(
				'error' => TRUE,
				'msg'   => 'El mensaje no existe.',
		));}
			
		$app->render(200,array('data' => $mensaje));
	});
?>
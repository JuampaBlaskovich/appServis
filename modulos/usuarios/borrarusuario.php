<?php
	//Funci�n para borrar un usuario.
	$app->delete('/borrarusuario/:id', function ($id) use ($app) {
		
		//Creo conexi�n, la paso a DB.
		$db = $app->db->getConnection();
		
		$user = User::find($id);
			
		if(empty($user)){
			$app->render(404,array(
				'error' => TRUE,
				'msg'   => 'Usuario no encontrado.',
			));
		}
		
		$user->delete();
		$app->render(200);
		
	});
?>
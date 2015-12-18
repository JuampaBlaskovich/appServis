<?php

	$app->delete('/borrarmensaje/:id', function ($id) use ($app) {
		
		$db = $app->db->getConnection();
		
		$mensaje = Mensaje::find($id);
			
		if(empty($mensaje)){
			$app->render(404,array(
				'error' => TRUE,
				'msg'   => 'El mensaje no existe.',
			));
		}
		
		$mensaje->delete();
		
		$app->render(200);
		
	});
?>
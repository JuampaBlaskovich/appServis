<?php
	//Método: GET.
	//Devuelve todos los datos del usuario.
	$app->get('/usuarios/:id', function ($id) use ($app) {
		
		//Creo conexión, la paso a DB.
		$db = $app->db->getConnection();
		
		//Busco usuario por ID. Si $user queda vacía en el if corta.
		$user = User::find($id);
		
		if(empty($user)){
			$app->render(404,array(
				'error' => TRUE,
				'msg'   => 'Usuario no encontrado.',
			));
		}
		
		//Horrible. Borra los campos password y email para que no se muestren.
		unset($user->password);
		
		//Seleccionar de la tabla en la BD 'posts', todos los títulos donde el 'id' del usuario que lo haya creado sea igual al id del usuario del que se quiere ver los posts.
		$user->posts = $db->table('posts')->select('title')->where('id_usuario', $user->id)->get();

		$app->render(200,array('data' => $user->toArray()));
	});
?>
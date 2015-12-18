<?php

	/**
	*
	*	Función: Identificar usuarios.
	*	Método: POST.
	*
	*	Se reciben los datos de la APP y se realizan las verificaciones
	*	necesarias: que no haya campos vacíos y que los datos introducidos
	*	coincidan con alguno de los usuarios en la base de datos.
	*
	*/
	
	$app->post('/login', function () use ($app) {
		
		
		$input = $app->request->getBody();
		
		$email = $input['email'];
		$password = $input['password'];
		

		if(empty($email)){

			$app->render(500,array(
				'error' => TRUE,
				'msg'   => 'Por favor, introduzca un E-Mail.',
			));}

		if(empty($password)){
			$app->render(500,array(
				'error' => TRUE,
				'msg'   => 'Por favor, introduzca una clave.',
			));}
		
		

		$db = $app->db->getConnection();
		
		$user = $db->table('usuarios')->select('id', 'name', 'password', 'auth')->where('email', $email)->first();

		if(empty($user)){
			$app->render(500,array(
				'error' => TRUE,
				'msg'   => 'El usuario ingresado no existe.',
			));
		}
		
		
		if($user->password != $password){
			$app->render(500,array(
				'error' => TRUE,
				'msg'   => 'La clave introducida no es correcta.',
			));
		}
		
		unset($user->password);
		
		$app->render(200,array('data' => $user));
		
	});
?>
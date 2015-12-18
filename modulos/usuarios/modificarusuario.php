<?php
	//Método: PUT.
	//¿Función para actualizar/cambiar los datos de un usuario según su ID?
	//Supongo, porque no crea ninguna nueva instancia del objeto User.
	$app->put('/usuarios/:id', function ($id) use ($app) {
		
		$input = $app->request->getBody();
		
		//Si el campo donde se debe introducir el nombre está vacío.
		$name = $input['name'];
		if(empty($name)){
			$app->render(500,array(
				'error' => TRUE,
				'msg'   => 'Por favor, introduzca un Nombre.',
			));
		}
		
		//Si el campo donde se debe introducir la clave está vacío.
		$password = $input['password'];
		if(empty($password)){
			$app->render(500,array(
				'error' => TRUE,
				'msg'   => 'Por favor, introduzca una clave.',
			));
		}
		
		//Si el campo donde se debe introducir el email está vacío.
		$email = $input['email'];
		if(empty($email)){
			$app->render(500,array(
				'error' => TRUE,
				'msg'   => 'Por favor, introduzca un E-Mail.',
			));
		}
		
		//Algoritmo que busca usuarios en base a su ID. Se guarda el resutlado en una variable local.
		$user = User::find($id);
		
		//Si la variable local está vacía entonces no se encontro ningún usuario con ese ID.
		if(empty($user)){
			$app->render(404,array(
				'error' => TRUE,
				'msg'   => 'Usuario no encontrado.',
			));
		}
		
		//Seteo todos los campos.
		$user->name = $name;
		$user->password = $password;
		$user->email = $email;
		$user->save();

		$app->render(200,array('data' => $user->toArray()));
	});
?>
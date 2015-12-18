<?php

	$app->post('/nuevousuario', function () use ($app) {
		
		$input = $app->request->getBody();

		$name = $input['name'];
		$email = $input['email'];
		$password = $input['password'];
		$confirmpassword = $input['confirmpassword'];
		
		$nameStr = strlen($name);
		$emailStr = strlen($email);
		$passwordStr = strlen($password);
		
		
		//Primero analizamos el campo Nombre.
			//No puede estar vacio.
			if(empty($name)){
				$app->render(500,array(
					'error' => TRUE,
					'msg'   => 'Por favor, introduzca un Nombre.',
				));
			}
			
			//Solo puede contener Letras y Espacios.
			if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
			   $app->render(500,array(
					'error' => TRUE,
					'msg'   => 'Tu nombre solo puede contener letras y espacios.',
				));
			}
			
			//Debe ser mayor a 6 caracteres.
			if($nameStr < 6){
				$app->render(500,array(
					'error' => TRUE,
					'msg'   => 'Tu nombre debe contener mas de 6 caracteres.',
				));
			}
		
		
		//Analizamos campo Email
			//No puede estar vacio.
			if(empty($email)){
				$app->render(500,array(
					'error' => TRUE,
					'msg'   => 'Por favor, introduzca un E-Mail.',
				));
			}
			
			//Tiene que tener el formato de un email.
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$app->render(500,array(
					'error' => TRUE,
					'msg'   => 'Por favor, introduzca un E-Mail valido.',
				));
			}
			
			//Un E-Mail no tiene menos de 10 letras.
			if($emailStr < 10){
				$app->render(500,array(
					'error' => TRUE,
					'msg'   => 'E-Mail demasiado corto. Introduzca un E-Mail valido.',
				));
			}
			
			//Vamos a corroborar que no existan dos usuarios con el mismo E-Mail.
			$db = $app->db->getConnection();

			$coincidencias = $db->table('usuarios')->select('id')->where('email', $email)->get();
			
			if(!empty($coincidencias)){
				$app->render(500,array(
						'error' => TRUE,
						'msg'   => 'Ya existe un usuario registrado con ese E-Mail.',
					));
			}
		
		
		//Analizamos el campo Clave.
			//No puede estar vacio.
			if(empty($password)){
				$app->render(500,array(
					'error' => TRUE,
					'msg'   => 'Por favor, introduzca una clave.',
				));
			}
			
			//Debe contener al menos 4 caracteres.
			if($passwordStr < 4){
				$app->render(500,array(
					'error' => TRUE,
					'msg'   => 'Tu clave debe contener mas de 4 caracteres.',
				));
			}
		
			//Las claves deben coincidir.
			if($password != $confirmpassword){
				$app->render(500,array(
					'error' => TRUE,
					'msg'   => 'Las claves introducidas no coinciden.',
				));
			}
		
		
		//Si se llegó a esté punto, todo está "OK".
		$user = new User();
		
		$user->name 	= $name;
		$user->password = $password;
		$user->email 	= $email;
		$user->auth 	= 0;
		$user->save();
		
		
		//Ahora busco en la base de datos al usuario recién creado para buscar su ID.
		$db = $app->db->getConnection();
		$id = $db->table('usuarios')->select('id')->where('email', $email)->get();
		
		//Genero un código único en base a su ID (Es único) y la clave de encriptación de la API.
		$codigo= simple_encrypt($id[0]->id, $app->enc_key);
		$codigo = substr($codigo, 0, 6); //Código de longitud 6.
		
		//Mediante el Plugin Sendgrid envio un email con el codigo:
		$sendgrid = new SendGrid('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');
			
			$sendemail    = new SendGrid\Email();

			$sendemail	->addTo($email) 									//Destinatario.
						->setFrom("appServiceTeam@gmail.com") 				//Remitente.
						->setSubject("[AppServis] Confirma tu cuenta.") 	//Asunto.
						->setHtml('Tu c&oacute;digo es: <strong style="font-size: 20px;">' . $codigo . '</strong> - Deber&aacute;s ingresaro cuando entres por primera vez a la aplicaci&oacute;n.');
		
			$sendgrid->send($sendemail);
		
		$app->render(200);
	});
?>
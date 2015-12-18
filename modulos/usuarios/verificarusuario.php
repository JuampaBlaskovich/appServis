<?php

	$app->put('/verificarusuario/:id', function ($id) use ($app) {
			
			$input = $app->request->getBody();
			$codrecibido = $input['codigo'];
			
			//Se crea el mismo código que se le fue enviado al usuario para comparar.
			$codcorrecto = simple_encrypt($id, $app->enc_key);
			$codcorrecto = substr($codcorrecto, 0, 6);
			
			//Por motivos de DEBUG se muestra el código correcto en caso de ser erroneo.
			if($codrecibido != $codcorrecto){
				$app->render(500,array(
						'error' => TRUE,
						'msg'   => 'Codigo incorrecto. <br>El codigo correcto es <strong>' . $codcorrecto . '</strong>'
					));
			}
			
			$user = User::find($id);
			
			$user->auth = 1; //Incremento AUTH (1 = Usuario Verificado).
			$user->save();
			
			$app->render(200);
	});
?>
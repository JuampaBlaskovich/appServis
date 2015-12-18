<?php

	ini_set('display_errors',1);
	ini_set('display_startup_errors',1);
	error_reporting(-1);

	require 'vendor/autoload.php';
	require 'Models/User.php';
	require 'Models/Post.php';
	require 'Models/Mensaje.php';
	
	
	//Funciones de encriptación y desencriptación.
	function simple_encrypt($text,$salt){  
		return crypt($text, $salt);
	}
 
	function simple_decrypt($text,$salt){  
		return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $salt, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
	}
	
	
	$app = new \Slim\Slim();
	
	
	//Clave encriptación de la APP:
	$app->enc_key = '1234567891011121';
	
	//Información de la Base de Datos.
	$app->config('databases', [
		'default' => [
			'driver'    => 'mysql',
			'host'      => 'eu-cdbr-west-01.cleardb.com',
			'database'  => 'heroku_53ae9716fdb16ba',
			'username'  => 'b3d11bcd2bf74a',
			'password'  => 'c4d85dcd',
			'charset'   => 'utf8',
			'collation' => 'utf8_general_ci',
			'prefix'    => ''
		]
	]);


	$app->add(new Zeuxisoo\Laravel\Database\Eloquent\ModelMiddleware);
	$app->view(new \JsonApiView());
	$app->add(new \JsonApiMiddleware());
	$app->add(new \Slim\Middleware\ContentTypes());

	
	$app->options('/(:name+)', function() use ($app) {
		$app->render(200,array('msg' => 'appService API V2'));
	});

	
	$app->get('/', function () use ($app) {
		$app->render(200,array('msg' => 'appService API V2'));
	});

	
	//Módulos:
	include 'modulos/login.php';
	include 'modulos/perfil.php';
	
	include 'modulos/usuarios/nuevousuario.php';
	include 'modulos/usuarios/verificarusuario.php';
	
	include 'modulos/usuarios/usuarios.php';
	include 'modulos/usuarios/modificarusuario.php';
	include 'modulos/usuarios/usuario.php';
	include 'modulos/usuarios/borrarusuario.php';
	
	include 'modulos/anuncios/listaranuncios.php';
	include 'modulos/anuncios/crearanuncio.php';
	include 'modulos/anuncios/anuncio.php';
	include 'modulos/anuncios/borraranuncio.php';	
	
	include 'modulos/favoritos/listarfavoritos.php';
	include 'modulos/favoritos/agregarfavorito.php';
	include 'modulos/favoritos/quitarfavorito.php';
	
	include 'modulos/mensajes/mensaje.php';
	include 'modulos/mensajes/listarmensajes.php';
	include 'modulos/mensajes/crearmensaje.php';
	include 'modulos/mensajes/borrarmensaje.php';

	$app->run();
?>
<?php
	//Inicializadores:
	ini_set('display_errors',1);
	ini_set('display_startup_errors',1);
	error_reporting(-1);
	
	//Requires:
	require 'vendor/autoload.php';
	require 'Models/User.php';
	require 'Models/Post.php';
	
	//Funciones de encriptación y desencriptación.
	function simple_encrypt($text,$salt){  
		return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $salt, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
	}
 
	function simple_decrypt($text,$salt){  
		return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $salt, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
	}
	
	//Creamos nueva instancia de la clase (Objeto).
	$app = new \Slim\Slim();
	
	//Clave encriptación:
	$app->enc_key = '1234567891234567';
	
	//Datos DB.
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
		$app->render(200,array('msg' => 'API BSKV'));
	});

	
	$app->get('/', function () use ($app) {
		$app->render(200,array('msg' => 'API BSKV'));
	});

	
	//Método: POST.
	//Función para loggear usuarios.
	$app->post('/login', function () use ($app) {
		
		$input = $app->request->getBody();
		
		//Si el campo E-Mail está vacio.
		$email = $input['email'];
		if(empty($email)){
			$app->render(500,array(
				'error' => TRUE,
				'msg'   => 'Por favor, introduzca un E-Mail.',
			));
		}
		
		//Si el campo Password está vacio.
		$password = $input['password'];
		if(empty($password)){
			$app->render(500,array(
				'error' => TRUE,
				'msg'   => 'Por favor, introduzca una clave.',
			));
		}
		
		//Si están los dos campos rellenos, llamo a la conexión.
		$db = $app->db->getConnection();
		
		
		//Selecciono de la tabla 'usuarios' la fila donde el campo 'email' coincide con el e-mail introducido.
		$user = $db->table('usuarios')->select()->where('email', $email)->first();
		
		
		//Si la base de datos devuelve un objeto vacío, el usuario no existe.
		//En la realidad hay que evitar dar este tipo de información y simplemente decir que la combinación no es correcta.
		//Por motivos de debug mostramos el texto.
		if(empty($user)){
			$app->render(500,array(
				'error' => TRUE,
				'msg'   => 'El usuario ingresado no existe.',
			));
		}
		
		//Si se encuentra una fila, pero la clave introducida no coincide con la clave devuelta por la base de datos para ese usuario.
		//Clave incorrecta.
		if($user->password != $password){
			$app->render(500,array(
				'error' => TRUE,
				'msg'   => 'La clave introducida no es correcta',
			));
		}
		
		unset($user->password);
		
		$app->render(200,array('data' => $user));
		
	});
	
	
	//Logout, destruimos la sesion.
	$app->get('/logout', function() use($app) {
		
		session_destroy();
		
		$app->redirect('/');
		
	});


	
	//Método: GET.
	$app->get('/me', function () use ($app) {
	
		//Busco el Token generado en el Login y lo paso a una variable local.
		$input = $app->request->getBody();
		$token = $input['token'];
	
		//Si el Token no existe (Vacío): El usuario no está logeado.
		if(empty($token)){
			$app->render(500,array(
				'error' => TRUE,
				'msg'   => 'Por favor, inicie sesion.',
			));
		}
	
		//Si el Token existe, lo desencripto y lo guardo en una variable local:
		$id_user_token = simple_decrypt($token, $app->enc_key);
		
		
		//Se ejecuta un algoritmo para verificar que el Token sea válido y guarda el resultado en una variable local.
		$user = User::find($id_user_token);
		
		//Si la variable está vacía es porque el token no es valido. El usuario no está logeado.
		if(empty($user)){
			$app->render(500,array(
				'error' => TRUE,
				'msg'   => 'Por favor, inicie sesion.',
			));
		}
		
		$app->render(200,array('data' => $user->toArray()));
	});
	
	//Método: GET.
	//Devuelve todos los usuarios de la base de datos.
	//Datos devueltos: Id, Nombre, Email, Auth.
	$app->get('/usuarios', function () use ($app) {
		$db = $app->db->getConnection();
		$users = $db->table('usuarios')->select('id', 'name', 'email', 'auth')->get();

		$app->render(200,array('data' => $users));
	});
	
	
	//Método: POST.
	//Crea nuevos usuarios.
	$app->post('/usuarios', function () use ($app) {
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
		
		//Si se llegó a esté punto, todo está "OK".
		$user = new User(); //Nuevo objeto de la clase User.
		$user->name = $name; //Seteo el nombre.
		$user->password = $password; //Seteo password.
		$user->email = $email; //Seteo email.
		$user->auth = 0;
		$user->save(); //Guardo el usuario.
		
		//Por defecto todos los usuarios van a tener el nivel de Auth 0 y solo se puede modificar desde la base de datos.

		$app->render(200,array('data' => $user->toArray()));
	});

	
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
	
	
	//Función para borrar un usuario.
	$app->delete('/borrarusuario', function ($id) use ($app) {
		
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
	
	
	//Devuelve todas las publicaciones
	$app->get('/posts', function () use ($app) {
		
		//Paso conexión a variable local.
		$db = $app->db->getConnection();
		
		//Paso publicaciones a variable local.
		$publicaciones = $db->table('posts')->select('id', 'title', 'descripcion')->get();
		
		//Si la vairable devuelve un valor vacío, no hay publicaciones.
		if(empty($publicaciones)){
			$app->render(404,array(
				'error' => TRUE,
				'msg'   => 'Aún no hay publicaciones en la aplicacion.',
			));
		}
		
		$app->render(200,array('data' => $publicaciones));
	});
	

	//Método: GET.
	//Busco post por ID.
	$app->get('/post/:id', function ($id) use ($app) {
		
		//Creo conexión, paso a variable local.
		$db = $app->db->getConnection();
		
		//Busco posts por ID.
		$post = Post::find($id);
		
		//Si la variable local quedó en cero es porque no se encontró ningún post.
		if(empty($post)){
			$app->render(404,array(
				'error' => TRUE,
				'msg'   => 'Post no encontrado.',
			));
		}
		
		//Seleccionar de la tabla usuarios, el nombre y email del 'id' del creador del post.
		$post->user = $db->table('usuarios')->select('id','name', 'email')->where('id', $post->id_usuario)->get();
		
		unset($post->id_usuario);
		
		$app->render(200,array('data' => $post->toArray()));
	});
	
	
	//Publicar un nuevo aviso.
	$app->post('/post', function () use ($app) {

		$input = $app->request->getBody();
		
		$title = $input['title'];
		if(empty($title)){
			$app->render(500,array(
				'error' => TRUE,
				'msg'   => 'Por favor, ingrese un titulo.',
			));
		}
		
		$descripcion = $input['descripcion'];
		if(empty($descripcion)){
			$app->render(500,array(
				'error' => TRUE,
				'msg'   => 'Por favor, ingrese una descripcion.',
			));
		}
		
		$userid = $input['idusuario'];
		
		$post = new Post();
		$post->title = $title;
		$post->id_usuario = $userid;
		$post->descripcion = $descripcion;
		$post->save();
		
		$app->render(200,array('data' => $post->toArray()));
	});
	
	
	//Método: GET.
	//En vez de buscar todos los usuarios o por ID, busca todos los posts del usuario actual que está consultando.
	$app->get('/profile', function () use ($app) {

		$db = $app->db->getConnection();
		
		$posts = $db->table('posts')->select()->where('id_usuario', $user->id)->get();
		
		$app->render(200,array('data' => $posts));
	});


$app->run();
?>
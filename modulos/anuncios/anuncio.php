<?php

	/**
	*
	*	Función: Mostrar anuncio individual.
	*	Método: GET.
	*
	*	La función se encarga de realizar todo el procesamiento
	*	necesario para visualizar correctamente un anuncio individual
	*	en base a su ID ($id) y el ID del usuario quien solicita verlo ($idusuario)
	*
	*/
	
	$app->get('/post/:id/:idusuario', function ($id, $idusuario) use ($app) {

		$db = $app->db->getConnection();
		
		$post = Post::find($id);

		if(empty($post)){
			$app->render(404,array(
				'error' => TRUE,
				'msg'   => 'Post no encontrado.',
			));}
		
		
		//Se incrementa la cantidad de visitas del anuncio x1.
		$post->visitas++;
		$post->save();
		
		
		//Se devuelve además el nombre y el e-mail del creador del anuncio.
		$post->user = $db->table('usuarios')->select('id','name', 'email')->where('id', $post->id_usuario)->get();
		
		
		/*
		*	Se determina si el usuario que está visualizando el anuncio tiene al mismo como favorito o no.
		* 	Para esto se checkean coincidencias en la tabla 'favoritos'. 
		*	
		*	Se adjunta además en la respuesta:
		*
		*	- El texto del botón dentro de la APP (esfavmsg)
		*	- Un valor BOOLEAN para determinar que función realizará el botón (esfav) (agregar o quitar de favs.).
		*/
		
		$post->esfav = $db->table('favoritos')->select()->where('id_usuario', $idusuario)->where('id_post', $id)->get();

		if(empty($post->esfav)){
			$post->esfav = false;
			$post->esfavmsg = 'Agregar a Favoritos';
		} else{
			$post->esfav = true;
			$post->esfavmsg = 'Quitar de Favoritos';
		}
		
	
		/*
		*	A continuación se determina si el usuario va a ser capaz de visualizar un botón dentro de la APP
		*	para borrar el anuncio. Esto va a ser solo si: 
		*
		*	- El usuario que esta viendo el anuncio ($idusuario) es el creador del mismo.
		*	- El usuario que está viendo el anuncio tiene un nivel de autorización ($auth)
		*	  lo suficientemente alto (Administrador).
		*/
		
		//$authlvl = $db->table('usuarios')->select('auth')->where('id', $idusuario)->get();
			
		if($idusuario == $post->id_usuario){
			$post->esadm = true;
		} else{
			$post->esadm = false;
		}
			
			
		$app->render(200,array('data' => $post->toArray()));
	});
?>
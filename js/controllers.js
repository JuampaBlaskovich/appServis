angular.module('starter.controllers', [])

.controller('AppCtrl', function($scope, $ionicModal, $timeout, $http, $location) {

   $scope.doLogout = function(){
    $http.get('http://api-geoalquiler.herokuapp.com/logout', {withCredentials: true}).then(function(resp) {
    $scope.user = resp.data.data;
    $location.path('/app/entrar');

    }, function(err) {
      console.error('ERR', err);
      // err.status will contain the status code
    });
    }
})

.controller('EntrarCtrl', function($scope, $stateParams, $http, $ionicPopup, $location ) {
  
        $scope.user={};
        $scope.user.email='';
        $scope.user.password =''; 
  
   $scope.doLogin = function() {
    $http.post('http://api-geoalquiler.herokuapp.com/login',$scope.user, {withCredentials: true}).then(function(resp) {
        console.log(resp.data);
         var alertPopup = $ionicPopup.alert({
             title: 'Logeado con exito',
             template: 'Ingresa ahora'
           });
           alertPopup.then(function(res) {
             $location.path('/app/usuarios');
           });
          
    }, function(err) {
      console.error('ERR', err);
      var alertPopup = $ionicPopup.alert({
             title: 'Error en el ingreso',
             template: 'Email o contraseña invalido'
           });
           alertPopup.then(function(res) {
             $location.path('/app/entrar');
           });
      // err.status will contain the status code
    });
    };
  
})

.controller('UsuarioslistsCtrl', function($scope, $http, $location) {
         
     $scope.user = [];
    $http.get('http://api-geoalquiler.herokuapp.com/index.php/me', {withCredentials: true}).then(function(resp) {
      $scope.user = resp.data.data;
      console.log('Succes', resp.data.data);
      $location.path('/app/usuarios');
    }, function(err) {
      console.error('ERR', err);
      $location.path('/app/entrar');
      // err.status will contain the status code
    });


  $scope.usuarios = [];
   $scope.$on('$ionicView.beforeEnter', function() {
    $http.get('http://api-geoalquiler.herokuapp.com/index.php/usuarios').then(function(resp) {
      $scope.usuarios = resp.data.data;
      console.log('Succes', resp.data.data);
    }, function(err) {
      console.error('ERR', err);
      // err.status will contain the status code
    });
  });

})

.controller('UsuarioCtrl', function($scope, $stateParams, $http, $location) {


  $scope.usuario = {};
  $http.get('http://api-geoalquiler.herokuapp.com/index.php/usuarios/'+ $stateParams.UsuarioId).then(function(resp) {
    $scope.usuario = resp.data.data;
  }, function(err) {
    console.error('ERR', err);
    // err.status will contain the status code
  });

  $scope.doSave = function() {
    $http.put('http://api-geoalquiler.herokuapp.com/index.php/usuarios/'+ $stateParams.UsuarioId, $scope.usuario).then(function(resp) {
      console.log(resp.data);
      $location.path('/app/usuarios');
    }, function(err) {
      console.error('ERR', err);
      // err.status will contain the status code
    });

  };

  $scope.doDelete = function() {
    $http.delete('http://api-geoalquiler.herokuapp.com/index.php/usuarios/'+ $stateParams.UsuarioId, $scope.usuario).then(function(resp) {
      console.log(resp.data);
      $location.path('/app/usuarios');
    }, function(err) {
      console.error('ERR', err);
      // err.status will contain the status code
    });
  };

  $scope.doPremiun = function() {
    $http.put('http://api-geoalquiler.herokuapp.com/index.php/usuariostipo/'+ $stateParams.UsuarioId, $scope.usuariotipo).then(function(resp) {
      console.log(resp.data);
      $location.path('/app/usuarios');
    }, function(err) {
      console.error('ERR', err);
      // err.status will contain the status code
    });

  };

  $scope.doFree = function() {
    $http.put('http://api-geoalquiler.herokuapp.com/index.php/usuariostipo/'+ $stateParams.UsuarioId, $scope.usuariotipo).then(function(resp) {
      console.log(resp.data);
      $location.path('/app/usuarios');
    }, function(err) {
      console.error('ERR', err);
      // err.status will contain the status code
    });

  };

})

.controller('NuevoUsuarioCtrl', function($scope, $stateParams, $http, $ionicPopup, $location ) {
    
      $scope.user = [];
    $http.get('http://api-geoalquiler.herokuapp.com/index.php/me', {withCredentials: true}).then(function(resp) {
      $scope.user = resp.data.data;
      console.log('Succes', resp.data.data);
      $location.path('/app/nuevousuario');
    }, function(err) {
      console.error('ERR', err);
      $location.path('/app/entrar');
      // err.status will contain the status code
    });
        
        $scope.user={};
        $scope.user.picture='';
        $scope.user.name='';
        $scope.user.email='';
        $scope.user.id =''; 
  
   $scope.doRegister = function() {
      $http.post('http://api-geoalquiler.herokuapp.com/usuarios',$scope.user ).then(function(resp) {
        console.log(resp.data);
         var alertPopup = $ionicPopup.alert({
             title: 'Usuario Creado con exito',
             template: 'Ingresa ahora'
           });
           alertPopup.then(function(res) {
             $location.path('/app/usuarios');
           });
          
    }, function(err) {
      console.error('ERR', err);
      // err.status will contain the status code
    });
    };
  
})

.controller('AvisoslistsCtrl', function($scope, $http) {

   $scope.user = [];
    $http.get('http://api-geoalquiler.herokuapp.com/index.php/me', {withCredentials: true}).then(function(resp) {
      $scope.user = resp.data.data;
      console.log('Succes', resp.data.data);
      $location.path('/app/avisos');
    }, function(err) {
      console.error('ERR', err);
      $location.path('/app/entrar');
      // err.status will contain the status code
    });

  
  $scope.avisos = [];
   $scope.$on('$ionicView.beforeEnter', function() {
  $http.get('http://api-geoalquiler.herokuapp.com/index.php/anuncios').then(function(resp) {
    $scope.avisos = resp.data.data;
    console.log('Succes', resp.data.data);
  }, function(err) {
    console.error('ERR', err);
    // err.status will contain the status code
  });
  });
})

.controller('AvisoCtrl', function($scope, $stateParams, $http, $location) {

  $scope.aviso = {};

  $http.get('http://api-geoalquiler.herokuapp.com/index.php/anuncios/'+ $stateParams.AvisoId).then(function(resp) {
    $scope.aviso = resp.data.data;
  }, function(err) {
    console.error('ERR', err);
    // err.status will contain the status code
  });

  $scope.doSave = function() {
    $http.put('http://api-geoalquiler.herokuapp.com/index.php/anuncios/'+ $stateParams.AvisoId, $scope.aviso).then(function(resp) {
      console.log(resp.data);
      $location.path('/app/avisos');
    }, function(err) {
      console.error('ERR', err);
      // err.status will contain the status code
    });

  };

   $scope.doDelete = function() {
    $http.delete('http://api-geoalquiler.herokuapp.com/index.php/anuncios/'+ $stateParams.AvisoId, $scope.aviso).then(function(resp) {
      console.log(resp.data);
      $location.path('/app/avisos');
    }, function(err) {
      console.error('ERR', err);
      // err.status will contain the status code
    });
  };

})


.controller('NuevoAvisoCtrl', function($scope, $stateParams, $http, $ionicPopup, $location ) {

    $scope.user = [];
    $http.get('http://api-geoalquiler.herokuapp.com/index.php/me', {withCredentials: true}).then(function(resp) {
      $scope.user = resp.data.data;
      console.log('Succes', resp.data.data);
      $location.path('/app/nuevoaviso');
    }, function(err) {
      console.error('ERR', err);
      $location.path('/app/entrar');
      // err.status will contain the status code
    });
  
        $scope.anuncios={};
        $scope.anuncios.titulo='';
        $scope.anuncios.descripcion='';
        $scope.anuncios.precio='';
  
   $scope.doRegister = function() {
      $http.post('http://api-geoalquiler.herokuapp.com/anuncios',$scope.anuncios ).then(function(resp) {
        console.log(resp.data);
         var alertPopup = $ionicPopup.alert({
             title: 'Anuncio Creado con exito',
             template: 'Vea su anuncion publicado'
           });
           alertPopup.then(function(res) {
             $location.path('/app/avisos');
           });
          
    }, function(err) {
      console.error('ERR', err);
      // err.status will contain the status code
    });
    };
  
});

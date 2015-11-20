angular.module('starter.controllers', [])

.controller('AppCtrl', function($scope, $ionicModal, $timeout, $http, $location){

    $scope.doLogout = function () {
        $http.get('http://appservis.herokuapp.com/logout', { withCredentials: true }).then(function (resp) {
            $scope.user = resp.data.data;
            $location.path('/app/login');

        }, function (err) {
            console.error('ERR', err);
        });
    }

})

.controller('LoginCtrl', function($scope, $stateParams, $http, $ionicPopup, $location ) {
  
    $scope.user = {};
    $scope.user.email='';
    $scope.user.password ='';
  
    $scope.doLogin = function () {

        $http.post('http://appservis.herokuapp.com/login', $scope.user, { withCredentials: true }).then(function (resp) {
            console.log(resp.data);

            //Guardo el ID del usuario.
            $scope.user.id = resp.data.data.id;
            $scope.user.auth = resp.data.data.auth;
            $scope.user.name = resp.data.data.name;

            localStorage.setItem("id", $scope.user.id);
            localStorage.setItem("auth", $scope.user.auth);
            localStorage.setItem("name", $scope.user.name);

            $location.path('/app/avisos');

        },

        function (err) {
            console.error('ERR', err);
            var alertPopup = $ionicPopup.alert({
            title: 'Error',
            template: 'Datos incorrectos.'
        });

        alertPopup.then(function(res) {
            $location.path('/app/login');
        });

        });
    };
  
})

.controller('UsuarioslistsCtrl', function($scope, $http, $location) {
         
    $scope.usuarios = [];

    $scope.$on('$ionicView.beforeEnter', function() {
        $http.get('http://appservis.herokuapp.com/usuarios').then(function (resp) {
        $scope.usuarios = resp.data.data;
        console.log('Succes', resp.data.data);
    }, function(err) {
        console.error('ERR', err);
    });

    });

})

.controller('UsuarioCtrl', function($scope, $stateParams, $http, $location) {

    $scope.usuario = {};

    $userauth = localStorage.getItem("auth");

    $scope.esAdmin = function () {
        if ($userauth == 1) {
            return 'ng-show';
        } else {
            return 'ng-hide';
        }
    }

  $http.get('http://appservis.herokuapp.com/usuarios/' + $stateParams.UsuarioId).then(function (resp) {
      $scope.usuario = resp.data.data;

  }, function(err) {
    console.error('ERR', err);
    // err.status will contain the status code
  });


    //No funciona. Nos devuelve error de Host.
    $scope.doDelete = function() {
        $http.delete('http://appservis.herokuapp.com/borrarusuario' +$stateParams.UsuarioId, $scope.usuario).then(function (resp) {
            console.log(resp.data);

        $location.path('/app/usuarios');
    }, function(err) {
        console.error('ERR', err);
        // err.status will contain the status code
    });
    };

})

.controller('NuevoUsuarioCtrl', function($scope, $stateParams, $http, $ionicPopup, $location ) {
        
    $scope.user={};
    $scope.user.name='';
    $scope.user.email = '';
    $scope.user.password = '';
  
    $scope.doRegister = function() {
        $http.post('http://appservis.herokuapp.com/usuarios', $scope.user).then(function (resp) {

            console.log(resp.data);

        var alertPopup = $ionicPopup.alert({
                title: 'Usuario Creado con exito',
                template: 'Ingresa ahora'
            });

            alertPopup.then(function(res) {
                $location.path('/login');
            });
          
    }, function(err) {
        console.error('ERR', err);
        // err.status will contain the status code
    });
    };
  
})

.controller('AvisoslistsCtrl', function($scope, $http) {

    $scope.avisos = [];

    $scope.$on('$ionicView.beforeEnter', function () {
        $http.get('http://appservis.herokuapp.com/posts').then(function (resp) {
    
            $scope.avisos = resp.data.data;
        console.log('Succes', resp.data.data);
    }, function(err) {
        console.error('ERR', err);
        // err.status will contain the status code
    });
    });

    $scope.nombreUsuario = function () {
        return localStorage.getItem("name");
    }
})

.controller('AvisoCtrl', function($scope, $stateParams, $http, $location) {

    $scope.aviso = {};
    $userauth = localStorage.getItem("auth");

  $http.get('http://appservis.herokuapp.com/post/' + $stateParams.AvisoId).then(function (resp) {
      $scope.aviso = resp.data.data;
      $scope.avisousuario = resp.data.data.user[0];

  }, function(err) {
    console.error('ERR', err);
    // err.status will contain the status code
  });


  $scope.esAdmin = function () {
      if ($userauth == 1) {
          return 'ng-show';
      } else {
          return 'ng-hide';
      }
  }

    //No funciona. Nos devuelve error de Host.
   $scope.doDelete = function() {
       $http.delete('http://appservis.herokuapp.com/index.php/post/' + $stateParams.AvisoId, $scope.aviso).then(function (resp) {
      console.log(resp.data);
      $location.path('/app/avisos');

    }, function(err) {
      console.error('ERR', err);
      // err.status will contain the status code
    });
  };

})


.controller('NuevoAvisoCtrl', function($scope, $stateParams, $http, $ionicPopup, $location ) {

    $scope.anuncios={};
    $scope.anuncios.title='';
    $scope.anuncios.descripcion = '';
    $scope.anuncios.idusuario = localStorage.getItem("id");
  
   $scope.doRegister = function() {
       $http.post('http://appservis.herokuapp.com/post', $scope.anuncios).then(function (resp) {
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

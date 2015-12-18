angular.module('starter.controllers', [])

.controller('AppCtrl', function ($scope, $ionicModal, $timeout, $location) {

    //Obtengo nombre de usuario para mostrar.
    $scope.usuario = sessionStorage.getItem("name");

    //Si el usuario se deconecta, borro valores guardados.
    $scope.doLogout = function () {

        localStorage.clear();

        console.log('Desconectado');

        $location.path('/login');
    };
    
})


.controller('LoginCtrl', function ($scope, $http, $ionicPopup, $location, $ionicLoading) {

    //Prototipos.
    $scope.user = {};
    $scope.user.email = '';
    $scope.user.password = '';

    $scope.doLogin = function () {
            
        //Muestro icono "Cargando".
        $ionicLoading.show({
            template: '<ion-spinner class="spinner"></ion-spinner>'
        });

        //Request API.
        $http.post('http://appservis.herokuapp.com/login', $scope.user, {withCredentials: true}).then(function (resp) {
            console.log(resp.data);

            //Guardo datos sesion devueltos:
            sessionStorage.setItem("id", resp.data.data.id); //ID.
            sessionStorage.setItem("auth", resp.data.data.auth); //Nivel.
            sessionStorage.setItem("name", resp.data.data.name); //Nombre.

            //Saco el icono de Cargando.
            $ionicLoading.hide();

            //En base al AUTH redirijo donde corresponda
            switch (resp.data.data.auth) {
                case 0: //Usuario no verificado.
                    $location.path('/verifyaccount'); 
                    break;

                case 1: //Usuario recurrente.
                    $location.path('/app/anuncios');
                    break;

                default: //default.
                    $location.path('/app/anuncios');
            }

        },

        function (err) {

            console.error('ERR', err); // Debug.

            //Saco el icono de Cargando.
            $ionicLoading.hide();

            //Pre-cargo valores pop-up.
            var alertPopup = $ionicPopup.alert({
                title: 'Error',
                template: err.data.msg
            });

            //Muestro pop-up.
            alertPopup.then(function(res){});

        });
    };
})

.controller('NewUserCtrl', function ($scope, $http, $ionicPopup, $location, $ionicLoading) {

    $scope.user = {};
    $scope.user.name = '';
    $scope.user.email = '';
    $scope.user.password = '';
    $scope.user.confirmpassword = '';

    $scope.doRegister = function () {

        //Muestro icono "Cargando".
        $ionicLoading.show({
            template: '<ion-spinner class="spinner"></ion-spinner>'
        });

        $http.post('http://appservis.herokuapp.com/nuevousuario', $scope.user).then(function (resp) {

            console.error(resp.data);

            //Saco el icono de Cargando.
            $ionicLoading.hide();

            var alertPopup = $ionicPopup.alert({
                title: 'Usuario Creado con &Eacute;xito',
                template: 'Se ha enviado un c&oacute;digo de confirmaci&oacute;n a tu correo electr&oacute;nico.'
            });

            alertPopup.then(function (res) {
                $location.path('/login');
            });

        }, function (err) {

            //Saco el icono de Cargando.
            $ionicLoading.hide();

            var alertPopup = $ionicPopup.alert({
                title: 'Error',
                template: err.data.msg
            });

            alertPopup.then(function (res) { });
        });
    };

})

.controller('VerifyAccCtrl', function ($scope, $http, $location, $ionicPopup, $ionicLoading) {

    $scope.user = {};
    $scope.user.codigo = '';
    $idUsuario = sessionStorage.getItem("id");

    $scope.doVerify = function () {

        //Muestro icono "Cargando".
        $ionicLoading.show({
            template: '<ion-spinner class="spinner"></ion-spinner>'
        });

        $http.put('http://appservis.herokuapp.com/verificarusuario/' + $idUsuario, $scope.user).then(function (resp) {

            console.log(resp.data);

            //Saco el icono de Cargando.
            $ionicLoading.hide();

            //Redirijo a los Anuncios.
            $location.path('/app/anuncios');

        }, function (err) {
            console.error('ERR', err);
           
            //Saco el icono de Cargando.
            $ionicLoading.hide();

            var alertPopup = $ionicPopup.alert({
                title: 'Error',
                template: err.data.msg
            });

            alertPopup.then(function (res) { });

        });

    };
})


.controller('AnunciosCtrl', function ($scope, $http) {

    $scope.anuncios = [];

    $scope.$on('$ionicView.beforeEnter', function () {

        $http.get('http://appservis.herokuapp.com/posts').then(function (resp) {

            $scope.anuncios = resp.data.data;

            console.log('Succes', resp.data.data);

        }, function (err) {

            console.error('ERR', err);
            // err.status will contain the status code

        });
    });

})

.controller('AnuncioCtrl', function ($scope, $http, $stateParams, $ionicLoading, $ionicPopup, $location, $ionicHistory) {

    //Muestro icono "Cargando".
    $ionicLoading.show({
        template: '<ion-spinner class="spinner"></ion-spinner>'
    });

    $scope.anuncio = {};
    $idUsuario = sessionStorage.getItem("id");
    //$userauth = sessionStorage.getItem("auth");

    $scope.$on('$ionicView.beforeEnter', function () {
        $http.get('http://appservis.herokuapp.com/post/' + $stateParams.IdAnuncio + '/' + $idUsuario).then(function (resp) {

            //Saco el icono de Cargando.
            $ionicLoading.hide();

            $scope.anuncio = resp.data.data;
            $scope.anuncio.created_at = $scope.anuncio.created_at.substr(0, 10);
            $scope.usuario = resp.data.data.user[0];

            console.log($scope.anuncio);

        }, function (err) {

            //Saco el icono de Cargando.
            $ionicLoading.hide();

            console.error('ERR', err);
            // err.status will contain the status code
        });
    });


    $scope.doFavorito = function () {
        if (!($scope.anuncio.esfav)) {

            console.log('Favoriteado!');

            $http.put('http://appservis.herokuapp.com/agregarfavoritos/' + $stateParams.IdAnuncio + '/' + $idUsuario).then(function (resp) {

                $ionicHistory.nextViewOptions({
                    disableBack: true
                });

                $location.path('/app/favoritos');

            }, function (err) {

                console.error('ERR', err);
                // err.status will contain the status code

                var alertPopup = $ionicPopup.alert({
                    title: 'Error',
                    template: err.data.msg
                });

                alertPopup.then(function (res) { });

            });

        } else {

            console.log('DisFavoriteado!');

            $http.delete('http://appservis.herokuapp.com/quitarfavorito/' + $stateParams.IdAnuncio + '/' + $idUsuario).then(function (resp) {

                $ionicHistory.nextViewOptions({
                    disableBack: true
                });

                $location.path('/app/favoritos');

            }, function (err) {

                console.error('ERR', err);
                // err.status will contain the status code

                var alertPopup = $ionicPopup.alert({
                    title: 'Error',
                    template: err.data.msg
                });

                alertPopup.then(function (res) { });

            });
        }
    };


    $scope.esAdmin = function () {

        if ($scope.anuncio.esadm == 1) {
            return 'ng-show';
        } else {
            return 'ng-hide';
        }

    };


    $scope.doBorrar = function () {

        console.log('Borrado!');

        $http.delete('http://appservis.herokuapp.com/borraranuncio/' + $stateParams.IdAnuncio).then(function (resp) {

            $ionicHistory.nextViewOptions({
                disableBack: true
            });

            $location.path('/app/anuncios');

        }, function (err) {

            console.error('ERR', err);
            // err.status will contain the status code

            var alertPopup = $ionicPopup.alert({
                title: 'Error',
                template: err.data.msg
            });

            alertPopup.then(function (res) { });

        });
    };
})

.controller('CrearAnuncioCtrl', function ($scope, $http, $location, $ionicPopup, $ionicLoading, $ionicHistory) {

    $scope.anuncio = {};

    $scope.anuncio.title = '';
    $scope.anuncio.descripcion = '';
    $scope.anuncio.idusuario = sessionStorage.getItem("id");

    $scope.doCreate = function () {

        $http.post('http://appservis.herokuapp.com/post', $scope.anuncio).then(function (resp) {

            console.log(resp.data);

            var alertPopup = $ionicPopup.alert({
                title: 'Anuncio Creado con exito',
                template: 'Vea su anuncion publicado.'
            });

            alertPopup.then(function (res) {

                $ionicHistory.nextViewOptions({
                    disableBack: true
                });

                $location.path('/app/anuncios');

            });

        }, function (err) {

            console.error('ERR', err);

            var alertPopup = $ionicPopup.alert({
                title: 'Error',
                template: err.data.msg
            });

            alertPopup.then(function (res) { });

        });

    };

})

.controller('CrearMensajeCtrl', function ($scope, $stateParams, $http, $location, $ionicPopup, $ionicLoading, $ionicHistory) {

    $scope.mensaje = {};

    $scope.mensaje.asunto = '';
    $scope.mensaje.mensaje = '';
    $scope.mensaje.id_origen = sessionStorage.getItem("id");

    $scope.doSend = function () {

        $http.post('http://appservis.herokuapp.com/crearmensaje/' + $stateParams.IdUsuario, $scope.mensaje).then(function (resp) {

            console.log('Creado');

            var alertPopup = $ionicPopup.alert({
                title: 'Perfecto!',
                template: '¡Mensaje enviado!'
            });

            alertPopup.then(function (res) {
                $ionicHistory.goBack();
            });

        }, function (err) {

            console.error('ERR', err);

            var alertPopup = $ionicPopup.alert({
                title: 'Error',
                template: err.data.msg
            });

            alertPopup.then(function (res) {});

        });

    };

})

.controller('MensajesCtrl', function ($scope, $http, $stateParams) {

    $scope.mensajes = [];
    $IdUsuario = sessionStorage.getItem("id");

    $scope.$on('$ionicView.beforeEnter', function () {

        $http.get('http://appservis.herokuapp.com/listarmensajes/' + $IdUsuario).then(function (resp) {

            $scope.mensajes = resp.data.data;

        }, function (err) {

            console.error('ERR', err);
            // err.status will contain the status code

        });
    });

})

.controller('FavoritosCtrl', function ($scope, $http, $stateParams) {

    $scope.anuncios = [];

    $idUsuario = sessionStorage.getItem("id");

    $scope.$on('$ionicView.beforeEnter', function () {

        $http.get('http://appservis.herokuapp.com/listarfavoritos/' + $idUsuario).then(function (resp) {

            $scope.anuncios = resp.data.data;

            console.log('Succes', resp.data.data);

        }, function (err) {

            console.error('ERR', err);
            // err.status will contain the status code

        });
    });
})

.controller('MensajeCtrl', function ($scope, $stateParams, $http, $location, $ionicPopup, $ionicLoading, $ionicHistory) {

    $scope.mensaje = {};

    $scope.$on('$ionicView.beforeEnter', function () {
        $http.get('http://appservis.herokuapp.com/mensaje/' + $stateParams.IdMensaje).then(function (resp) {

            //Saco el icono de Cargando.
            $ionicLoading.hide();

            $scope.mensaje = resp.data.data[0];

            console.log($scope.mensaje);

        }, function (err) {

            //Saco el icono de Cargando.
            $ionicLoading.hide();

            console.error('ERR', err);
            // err.status will contain the status code
        });
    });

    $scope.doBorrar = function () {

        console.log('Borrado!');

        $http.delete('http://appservis.herokuapp.com/borrarmensaje/' + $stateParams.IdMensaje).then(function (resp) {

            $ionicHistory.nextViewOptions({
                disableBack: true
            });

            $location.path('/app/mensajes');

        }, function (err) {

            console.error('ERR', err);
            // err.status will contain the status code

            var alertPopup = $ionicPopup.alert({
                title: 'Error',
                template: err.data.msg
            });

            alertPopup.then(function (res) { });

        });
    };
})
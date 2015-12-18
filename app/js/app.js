// Ionic Starter App

// angular.module is a global place for creating, registering and retrieving Angular modules
// 'starter' is the name of this angular module example (also set in a <body> attribute in index.html)
// the 2nd parameter is an array of 'requires'
// 'starter.controllers' is found in controllers.js
angular.module('starter', ['ionic', 'starter.controllers'])

.run(function($ionicPlatform) {
  $ionicPlatform.ready(function() {
    // Hide the accessory bar by default (remove this to show the accessory bar above the keyboard
    // for form inputs)

    if (window.StatusBar) {
      // org.apache.cordova.statusbar required
      StatusBar.styleDefault();
    }
  });
})

.config(function ($stateProvider, $urlRouterProvider) {

    $stateProvider
	
    //App sin Menu:
	.state('login', {
		url: '/login',
		templateUrl: 'templates/login.html',
		controller: 'LoginCtrl'
	})

    .state('newuser', {
      	url: '/nuevousuario',
      	templateUrl: 'templates/nuevousuario.html',
      	controller: 'NewUserCtrl'
    })

    .state('verifyaccount', {
        url: '/verifyaccount',
        templateUrl: 'templates/verificar.html',
        controller: 'VerifyAccCtrl'
    })
	
     //App Con menu:
    .state('app', {
    url: '/app',
    abstract: true,
    templateUrl: 'templates/menu.html',
    controller: 'AppCtrl'
  })

      .state('app.anuncios', {
        url: '/anuncios',
        views: {
          'menuContent': {
              templateUrl: 'templates/anuncios.html',
              controller: 'AnunciosCtrl'
          }
        }
      })

    .state('app.anuncio', {
        url: '/anuncio/:IdAnuncio',
        views: {
            'menuContent': {
                templateUrl: 'templates/anuncio.html',
                controller: 'AnuncioCtrl'
            }
        }
    })

    .state('app.crearanuncio', {
        url: '/crearanuncio',
        views: {
            'menuContent': {
                templateUrl: 'templates/crearanuncio.html',
                controller: 'CrearAnuncioCtrl'
            }
        }
    })

    .state('app.favoritos', {
        url: '/favoritos',
        views: {
            'menuContent': {
                templateUrl: 'templates/favoritos.html',
                controller: 'FavoritosCtrl'
            }
        }
    })

    .state('app.mensajes', {
        url: '/mensajes',
        views: {
            'menuContent': {
            templateUrl: 'templates/mensajes.html',
            controller: 'MensajesCtrl'
            }
        }
    })

    .state('app.mensaje', {
        url: '/mensaje/:IdMensaje',
        views: {
            'menuContent': {
                templateUrl: 'templates/mensaje.html',
                controller: 'MensajeCtrl'
            }
        }
    })

    .state('app.crearmensaje', {
            url: '/crearmensaje/:IdUsuario',
            views: {
                'menuContent': {
                    templateUrl: 'templates/crearmensaje.html',
                    controller: 'CrearMensajeCtrl'
                }
            }
    })

    .state('app.perfil', {
        url: '/perfil',
        views: {
            'menuContent': {
                templateUrl: 'templates/perfil.html',
                controller: 'InvitarCtrl'
            }
        }
    });

      // if none of the above states are matched, use this as the fallback
      $urlRouterProvider.otherwise('/login');
});

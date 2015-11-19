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
    if (window.cordova && window.cordova.plugins.Keyboard) {
      cordova.plugins.Keyboard.hideKeyboardAccessoryBar(true);
      cordova.plugins.Keyboard.disableScroll(true);

    }
    if (window.StatusBar) {
      // org.apache.cordova.statusbar required
      StatusBar.styleDefault();
    }
  });
})

.config(function($stateProvider, $urlRouterProvider) {
  $stateProvider

    .state('app', {
    url: '/app',
    abstract: true,
    templateUrl: 'templates/menu.html',
    controller: 'AppCtrl'
  })

  .state('app.entrar', {
    url: '/entrar',
    views: {
      'menuContent': {
        templateUrl: 'templates/entrar.html',
        controller: 'EntrarCtrl'
      }
    }
  })

    .state('app.usuarios', {
      url: '/usuarios',
      views: {
        'menuContent': {
          templateUrl: 'templates/usuarios.html',
          controller: 'UsuarioslistsCtrl'
        }
      }
    })

  .state('app.usuario', {
    url: '/usuarios/:UsuarioId',
    views: {
      'menuContent': {
        templateUrl: 'templates/usuario.html',
        controller: 'UsuarioCtrl'
      }
    }
  })
  .state('app.nuevousuario', {
      url: '/nuevousuario',
      views: {
        'menuContent': {
          templateUrl: 'templates/nuevousuario.html',
          controller: 'NuevoUsuarioCtrl'
        }
      }
    })
  .state('app.avisos', {
    url: '/avisos',
    views: {
      'menuContent': {
        templateUrl: 'templates/avisos.html',
        controller: 'AvisoslistsCtrl'
      }
    }
  })
  .state('app.aviso', {
    url: '/avisos/:AvisoId',
    views: {
      'menuContent': {
        templateUrl: 'templates/aviso.html',
        controller: 'AvisoCtrl'
      }
    }
  })
  
 .state('app.nuevoaviso', {
      url: '/nuevoaviso',
      views: {
        'menuContent': {
          templateUrl: 'templates/nuevoaviso.html',
          controller: 'NuevoAvisoCtrl'
        }
      }
    }) ;

  // if none of the above states are matched, use this as the fallback
  $urlRouterProvider.otherwise('/app/entrar');
});

<?php

namespace App\Almacenes;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AlmacenesController implements ControllerProviderInterface
{

  // la función "connect" define las rutas del módulo
  public function connect(Application $app)
  {
    // creates a nuevo controlador
    $controller = $app['controllers_factory'];
     $almacenes = $app['session']->get('almacenes');
      if(!isset ($almacenes)){
        $almacenes = array(
        array(
          'id'=>1,
          'nombre'=>'o.o',
          'direccion'=>'asd',
          'ciudad'=>'asd',
          'sitio'=>'asd.com'          )
        );
        $app['session']->set('almacenes',$almacenes);
      }

    // la ruta "/almacenes/list"
    $controller->get('/list', function() use($app) {

      // obtiene el nombre de usuario de la sesión
      $user = $app['session']->get('user');
      $almacenes = $app['session']->get('almacenes');

      // ya ingreso un usuario ?
      if ( isset( $user ) && $user != '' ) {
        // muestra la plantilla
        return $app['twig']->render('Almacenes/almacenes.list.html.twig', array(
          'user' => $user,
          'almacenes' =>$almacenes
        ));

      } else {
        // redirige el navegador a "/login"
        return $app->redirect( $app['url_generator']->generate('login'));
      }

    // hace un bind
    })->bind('almacenes-list');

    // la ruta "/almacenes-edit"
    $controller->get('/almacenes-edit', function() use($app) {

      // obtiene el nombre de usuario de la sesión
      $user = $app['session']->get('user');

      // ya ingreso un usuario ?
      if ( isset( $user ) && $user != '' ) {
        // muestra la plantilla
        return $app['twig']->render('Almacenes/almacenes.edit.html.twig', array(
          'user' => $user
        ));

      } else {
        // redirige el navegador a "/login"
        return $app->redirect( $app['url_generator']->generate('login'));
      }

    // hace un bind
    })->bind('almacenes-edit');

    $controller->post('/almacen-save', function(Request $request) use($app){
      $almacenes = $app['session']->get('almacenes');

      $almacenes[] = array(
        'id' => $request->get('id'),
        'nombre' => $request->get('nombre'),
        'direccion' => $request->get('direccion'),
        'ciudad' => $request->get('ciudad'),
        'sitio' => $request->get('sitio')
      );

        $app['session']->set('almacenes', $almacenes);

      return $app->redirect( $app['url_generator']->generate('almacenes-list'));
    })->bind('almacen-save');



    // retorna el controlador
       return $controller;
  }
}

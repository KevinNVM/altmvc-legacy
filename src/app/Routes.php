<?php

/**
 * This is the routes file for your application where you can define your routes.
 * The router reads the route list from bottom to top, so be mindful of route overriding.
 * 
 * NOTE:
 *  - Wildcard names cannot contain numbers as it would render the route inaccessible.
 */

// Create a new instance of the global class `Router`
$router = new Router();

$router->get('/', array('HomeController', 'index'));

$router->get('/users', array('UserController', 'index'));
$router->post('/users', array('UserController', 'store'));
$router->put('/users/{userId}', array('UserController', 'update'));
$router->delete('/users/{userId}', array('UserController', 'destroy'));

$router->get('/about', array('HomeController', 'about'));
$router->get('/test-param/{param}/{param}', array('HomeController', 'testParam'));
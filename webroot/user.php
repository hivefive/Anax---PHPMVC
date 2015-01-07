<?php

$di->set('UsersController', function() use ($di) {
    $controller = new \Anax\Users\UsersController();
    $controller->setDI($di);
    return $controller;
});
    
$app->router->add('user', function() use ($app) {
	$app->theme->addStylesheet('css/user.css');
    $app->dispatcher->forward([
        'controller' => 'users',
        'action'     => 'list',
    ]);
});
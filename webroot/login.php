<?php


$app->router->add('login', function() use ($app) {

	$app->dispatcher->forward([
        'controller' => 'users',
        'action'     => 'login',
    ]);

});

$app->router->add('logout', function() use ($app) {

    $app->dispatcher->forward([
        'controller' => 'users',
        'action'     => 'logout',
    ]);

});

$app->router->add('profile', function() use ($app) {


    $app->dispatcher->forward([
        'controller' => 'users',
        'action'     => 'id',
    ]);

});
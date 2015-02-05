<?php

$app->router->add('setup', function() use ($app) {

	
	$app->dispatcher->forward([
        'controller' => 'questions',
        'action'     => 'setup',
    ]);
});
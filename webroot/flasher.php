<?php


$app->router->add('flashererror', function() use ($app) {

    $app->theme->setTitle("Flasher");
	$app->theme->addStylesheet('css/flasher.css');
	
	$app->flasher->setMessage('error', 'Error message!');
	
	if(isset($_SESSION['flash'])) {
        $app->views->add('me/flash', ['flash' => $app->flasher->getMessage()], 'flash');
        $app->flasher->clearFlash();
    }
	$app->views->add('me/page', [
        'content' => '',
	]);
});

$app->router->add('flasherwarning', function() use ($app) {

    $app->theme->setTitle("Flasher");
	$app->theme->addStylesheet('css/flasher.css');
	
	$app->flasher->setMessage('warning', 'This is a warning message!');
	
	if(isset($_SESSION['flash'])) {
        $app->views->add('me/flash', ['flash' => $app->flasher->getMessage()], 'flash');
        $app->flasher->clearFlash();
    }
	$app->views->add('me/page', [
        'content' => '',
	]);
});

$app->router->add('flasherinfo', function() use ($app) {

    $app->theme->setTitle("Flasher");
	$app->theme->addStylesheet('css/flasher.css');
	
	$app->flasher->setMessage('info');
	
	if(isset($_SESSION['flash'])) {
        $app->views->add('me/flash', ['flash' => $app->flasher->getMessage()], 'flash');
        $app->flasher->clearFlash();
    }
	$app->views->add('me/page', [
        'content' => '',
	]);
});

$app->router->add('flashersuccess', function() use ($app) {

    $app->theme->setTitle("Flasher");
	$app->theme->addStylesheet('css/flasher.css');
	
	$app->flasher->setMessage('success', 'Success message!');
	
	if(isset($_SESSION['flash'])) {
        $app->views->add('me/flash', ['flash' => $app->flasher->getMessage()], 'flash');
        $app->flasher->clearFlash();
    }
	$app->views->add('me/page', [
        'content' => '',
	]);
});
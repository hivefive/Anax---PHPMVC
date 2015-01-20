<?php
/**
 * This is a Anax pagecontroller.
 *
 */

// Get environment & autoloader and the $app-object.
require __DIR__.'/config_with_app.php';



$di->set('QuestionsController', function() use ($di) {
    $controller = new Anax\Questions\QuestionsController();
    $controller->setDI($di);
    return $controller;
});



$di->setShared('db', function() {
			$db = new \Mos\Database\CDatabaseBasic();
			$db->setOptions(require ANAX_APP_PATH . 'config/config_sqlite.php');
			$db->connect();
			return $db;
});

$di->set('flasher', '\Hivefive\CFlasher\CFlasher');

$app->theme->configure(ANAX_APP_PATH . 'config/theme-grid.php');
$app->navbar->configure(ANAX_APP_PATH . 'config/navbar_me.php');
/*$app->url->setUrlType(\Anax\Url\CUrl::URL_CLEAN);*/


// Set the title of the page
$app->theme->setVariable('title', "Min me-sida i PHPMVC");

$app->router->add('', function() use ($app) {
    
    $content = $app->fileContent->get('me.md');
    $content = $app->textFilter->doFilter($content, 'shortcode, markdown');
 
    $byline = $app->fileContent->get('byline.md');
    $byline = $app->textFilter->doFilter($byline, 'shortcode, markdown');
 
    $app->views->add('me/page', [
        'content' => $content,
        'byline' => $byline,
    ]);
    
    
    /*$thisPage = $app->request->getRoute(); 
	$setup = $app->dispatcher->forward([
        'controller' => 'questions',
        'action'     => 'setup',
		]);*/
		
    $questions = $app->dispatcher->forward([
        'controller' => 'questions',
        'action'     => 'latest',
		]);
		
	
	


}); 



$app->router->add('source', function() use ($app) {
	$app->theme->addStylesheet('css/source.css');
    $app->theme->setTitle("Source");
	
	$byline = $app->fileContent->get('byline.md');
    $byline = $app->textFilter->doFilter($byline, 'shortcode, markdown');
 
    $source = new \Mos\Source\CSource([
        'secure_dir' => '..', 
        'base_dir' => '..', 
        'add_ignore' => ['.htaccess'],
    ]);
 
    $app->views->add('me/source', [
        'content' => $source->View(),
    ],'flash');
	
	$app->views->addString(' ', 'main');
});




require_once('user.php');
require_once('login.php');

$app->router->handle();
$app->theme->render();

<?php
/**
 * This is a Anax pagecontroller.
 *
 */

// Get environment & autoloader and the $app-object.
require __DIR__.'/config_with_app.php';

$di->set('form', '\Mos\HTMLForm\CForm');

$di->setShared('db', function() {
			$db = new \Mos\Database\CDatabaseBasic();
			$db->setOptions(require ANAX_APP_PATH . 'config/config_mysql.php');
			$db->connect();
			return $db;
});

 $di->set('UsersController', function() use ($di) {
$controller = new \Anax\Users\UsersController();
$controller->setDI($di);
return $controller;
});
$di->set('CommentController', function() use ($di) {
$controller = new \Anax\Comment\CommentController();
$controller->setDI($di);
return $controller;
});

$di->set('QuestionsController', function() use ($di) {
$controller = new \Anax\Questions\QuestionsController();
$controller->setDI($di);
return $controller;
});

$di->set('TagsController', function() use ($di) {
$controller = new \Anax\Tags\TagsController();
$controller->setDI($di);
return $controller;
});

$di->set('flasher', '\Hivefive\CFlasher\CFlasher');

$app->theme->configure(ANAX_APP_PATH . 'config/theme-grid.php');
$app->navbar->configure(ANAX_APP_PATH . 'config/navbar_me.php');
//$app->url->setUrlType(\Anax\Url\CUrl::URL_CLEAN);

$app->session();


// Set the title of the page
$app->theme->setVariable('title', "Min me-sida i PHPMVC");

$app->router->add('', function() use ($app) {

    
    
    /*$thisPage = $app->request->getRoute(); 
	$setup = $app->dispatcher->forward([
        'controller' => 'questions',
        'action'     => 'setup',
		]);*/
		
    $questions = $app->dispatcher->forward([
        'controller' => 'questions',
        'action'     => 'latest',
		]);
		
	 $app->dispatcher->forward([
		'controller' => 'users',
		'action' => 'firstPage',
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


$app->router->add('ask', function() use ($app) {
    
    $questions = $app->dispatcher->forward([
        'controller' => 'questions',
        'action'     => 'view',
		]);
		

}); 

$app->router->add('tags', function() use ($app) {
    
    $questions = $app->dispatcher->forward([
        'controller' => 'tags',
        'action'     => 'list',
		]);
		

});

$app->router->add('about', function() use ($app) {
    
	$content = $app->fileContent->get('about.md');
    $content = $app->textFilter->doFilter($content, 'shortcode, markdown');
	
    $app->views->add('me/page', [
        'content' => $content,
    ],'main');
		

}); 

require_once('setup.php');
require_once('user.php');
require_once('login.php');



$app->router->handle();
$app->theme->render();

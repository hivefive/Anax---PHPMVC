<?php
/**
 * This is a Anax pagecontroller.
 *
 */

// Get environment & autoloader and the $app-object.
require __DIR__.'/config_with_app.php';



$di->set('CommentController', function() use ($di) {
    $controller = new Anax\Comment\CommentController();
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
    
    
    $thisPage = $app->request->getRoute(); 
    $app->dispatcher->forward([
        'controller' => 'comment',
        'action'     => 'view',
        'params' => [
            'page' => $thisPage,
				],
		]);
		
	$app->dispatcher->forward([
	'controller' => 'comment',
	'action'     => 'setup Comments',
	'params'     => [
					'page' => $thisPage,
					], 
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

$app->router->add('theme', function() use ($app) {

$app->theme->configure(ANAX_APP_PATH . 'config/theme-grid.php');
    $app->theme->setTitle("Mitt tema");
    $content = $app->fileContent->get('typography.html');
	$flash = $app->fileContent->get('themeimg.html');
	
	$app->views->add('me/page', [
        'content' => $content,
	]);
	
	$app->views->add('me/page', [
		'content' => $flash,
	
	],'flash');
	
	 $app->views->addString('<i class="fa fa-spinner fa-spin fa-2x"></i>', 'sidebar')
			   ->addString('<i class="fa fa-rebel fa-2x"></i>', 'sidebar')
			   ->addString('<i class="fa fa-rebel fa-spin fa-2x"></i>', 'sidebar')
			   ->addString('<i class="fa fa-rebel fa-3x"></i>', 'featured-1')
			   ->addString('<i class="fa fa-pie-chart fa-3x"></i>', 'featured-2')
			   ->addString('<i class="fa fa-check-circle-o fa-3x"></i>', 'featured-3');
			   
});
	

$app->router->add('regioner', function() use ($app) {
 $app->theme->configure(ANAX_APP_PATH . 'config/theme-grid.php');
    $app->theme->setTitle("Regioner");
	$app->theme->addStylesheet('css/showgrid.css');
    $app->views->addString('flash', 'flash')
               ->addString('featured-1', 'featured-1')
               ->addString('featured-2', 'featured-2')
               ->addString('featured-3', 'featured-3')
               ->addString('main', 'main')
               ->addString('sidebar', 'sidebar')
               ->addString('triptych-1', 'triptych-1')
               ->addString('triptych-2', 'triptych-2')
               ->addString('triptych-3', 'triptych-3')
               ->addString('footer-col-1', 'footer-col-1')
               ->addString('footer-col-2', 'footer-col-2')
               ->addString('footer-col-3', 'footer-col-3')
               ->addString('footer-col-4', 'footer-col-4');
 
});

$app->router->add('typografi', function() use ($app) {
	$app->theme->configure(ANAX_APP_PATH . 'config/theme-grid.php');
	$app->theme->setTitle("Typografi");
	
	$content = $app->fileContent->get('typography.html');
	
	 $app->views->add('me/page', [
        'content' => $content,
	]);
	
	$app->views->add('me/page', [
        'content' => $content,
	],'sidebar');

});

$app->router->add('ikoner', function() use ($app) {
$app->theme->configure(ANAX_APP_PATH . 'config/theme-grid.php');
	$app->theme->setTitle("Ikoner");

	$app->views->addString('<i class="fa fa-check-circle-o"></i>', 'flash')
               ->addString('<i class="fa fa-check-circle-o fa-2x"></i>', 'featured-1')
               ->addString('<i class="fa fa-check-circle-o fa-3x"></i>', 'featured-2')
               ->addString('<i class="fa fa-check-circle-o fa-4x"></i>', 'featured-3')
               ->addString('<i class="fa fa-check-circle-o fa-5x"></i>', 'main')
               ->addString('<i class="fa fa-spinner fa-spin"></i>', 'sidebar')
               ->addString('<i class="fa fa-spinner fa-spin fa-2x"></i>', 'triptych-1')
               ->addString('<i class="fa fa-spinner fa-spin fa-3x"></i>', 'triptych-2')
               ->addString('<i class="fa fa-spinner fa-spin fa-4x"></i>', 'triptych-3')
               ->addString('<i class="fa fa-spinner fa-spin fa-5x"></i>', 'footer-col-1')
               ->addString('<i class="fa fa-pie-chart"></i>', 'footer-col-2')
               ->addString('<i class="fa fa-pie-chart fa-spin fa-5x"></i>', 'footer-col-3')
               ->addString('<i class="fa fa-rebel fa-5x"></i>', 'footer-col-4');
});

require_once('user.php');
require_once('setup.php');
require_once('flasher.php');

$app->router->handle();
$app->theme->render();

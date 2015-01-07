<?php
/**
 * This is a Anax pagecontroller.
 *
 */

// Get environment & autoloader and the $app-object.
require __DIR__.'/config_with_app.php';



$di->set('CommentController', function() use ($di) {
    $controller = new Phpmvc\Comment\CommentController();
    $controller->setDI($di);
    return $controller;
});


// Set configuration for theme
$app->theme->configure(ANAX_APP_PATH . 'config/theme-grid.php');

// Set navbar
$app->navbar->configure(ANAX_APP_PATH . 'config/navbar_me.php');

$app->url->setUrlType(\Anax\Url\CUrl::URL_CLEAN);

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
    
    
    
    $app->dispatcher->forward([
        'controller' => 'comment',
        'action'     => 'view',
        'params' => [
            'key' => 'home',
            'redirect' => '',
                        
        ],
    ]); 

    $app->views->add('comment/form', [
        'mail'      => null,
        'web'       => null,
        'name'      => null,
        'content'   => null,
        'output'    => null,
        'key'         => 'home',
        'redirect'    => '',
    ]);
}); 

$app->router->add('regioner', function() use ($app) {
 
    $app->theme->addStylesheet('css/anax-grid/regions_demo.css');
    $app->theme->setTitle("Regioner");
 
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

$app->router->handle();
$app->theme->render();

<?php
/**
 * This is a Anax pagecontroller.
 *
 */

// Get environment & autoloader and the $app-object.
require __DIR__.'/config_with_app.php';

$app->theme->configure(ANAX_APP_PATH . 'config/theme_me.php');
$app->navbar->configure(ANAX_APP_PATH . 'config/navbar_me.php');

// Set the title of the page
$app->theme->setVariable('title', "Min me-sida i PHPMVC");

$app->router->add('', function() use ($app) {
	$content = $app->fileContent->get('me.md');
	$content = $app->textFilter->doFilter($content, 'shortcode, markdown');
	
	$app->views->add('me/page', [
		'content' => $content,
	]);
});

$app->router->add('redovisning', function() use ($app){
	$content = $app->fileContent->get('report.md');
	$content = $app->textFilter->doFilter($content, 'shortcode, markdown');
	
	$app->theme->setTitle("Redovisning");
    $app->views->add('me/page', [
		'content' => $content,
	]);
});

$app->router->add('source', function() use ($app) {
	$app->theme->addStylesheet('css/source.css');
    $app->theme->setTitle("Redovisning");
 
    $source = new \Mos\Source\CSource([
        'secure_dir' => '..', 
        'base_dir' => '..', 
        'add_ignore' => ['.htaccess'],
    ]);
 
    $app->views->add('me/source', [
        'content' => $source->View(),
    ]);
});

$app->router->handle();
$app->theme->render();

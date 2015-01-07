<?php
/**
 * Config-file for navigation bar.
 *
 */
return [

    // Use for styling the menu
    'class' => 'navbar',
 
    // Here comes the menu strcture
    'items' => [

        // This is a menu item
        'home'  => [
            'text'  => '<i class="fa fa-leaf"></i> Home',
            'url'   => '',
            'title' => 'Om mig'
        ],
		
		
		'users' => [
			'text' => '<i class="fa fa-users"></i> Users',
			'url' => 'user',
			'title' => 'Users'],
			
		
        // This is a menu item
        'source' => [
            'text'  =>'<i class="fa fa-cogs"></i> Source',
            'url'   => 'source',
            'title' => 'Source'
        ],
		
	],
 
    // Callback tracing the current selected menu item base on scriptname
    'callback' => function ($url) {
        if ($url == $this->di->get('request')->getRoute()) {
                return true;
        }
    },

    // Callback to create the urls
    'create_url' => function ($url) {
        return $this->di->get('url')->create($url);
    },
];

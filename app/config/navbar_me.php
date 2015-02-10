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
            'title' => 'Home'
        ],
		
		
		'users' => [
			'text' => '<i class="fa fa-users"></i> Users',
			'url' => 'user',
			'title' => 'Users'],
			
        'ask' => [
            'text'  =>'<i class="fa fa-comments"></i> Ask a question',
            'url'   => 'ask',
            'title' => 'Ask a question'
        ],
		
		'tags' => [
            'text'  =>'<i class="fa fa-bookmark"></i> All tags',
            'url'   => 'tags',
            'title' => 'All tags'
        ],
		
		'about' => [
            'text'  =>'<i class="fa fa-rocket"></i> About',
            'url'   => 'about',
            'title' => 'About'
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

<?php

$app->router->add('setup', function() use ($app) {

    $app->db->dropTableIfExists('Comments')->execute();
 
    $app->db->createTable(
        'Comment',
        [
            'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
            'mail' => ['varchar(80)'],
            'name' => ['varchar(80)'],
            'content' => ['text'],
            'web' => ['varchar(255)'],
            'timestamp' => ['datetime'],
            'ip' => ['varchar(255)'],
            'page' => ['varchar(80)'],
        ]
    )->execute();
});
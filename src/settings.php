<?php

return [
    'settings' => [
        'db' => new PDO("sqlite:/" . __DIR__ . "/../data/db.sqlite"),
        'jwt.secret_key' => '3cafe40f92be6ac77d2792b4b267c2da11e3f3087b93bb19c6c5133786984b44',
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'jwt-app',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],
    ],
];

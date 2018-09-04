<?php
// Application middleware

$app->add(new Tuupola\Middleware\JwtAuthentication([
    'path'   => ['/api', '/admin'],
    'ignore' => ['/api/auth'],
    'secret' => $app->getContainer()->get('settings')['jwt.secret_key']
]));
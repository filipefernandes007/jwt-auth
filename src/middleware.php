<?php
// Application middleware

$app->add(new Tuupola\Middleware\JwtAuthentication([
    'path'      => ['/api', '/admin'],
    'ignore'    => ['/api/auth'],
    'attribute' => 'jwt',
    'secret'    => getenv('JWT_SECRET')
]));
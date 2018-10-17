<?php
// Application middleware

$app->add(new Tuupola\Middleware\JwtAuthentication([
    'path'      => ['/api', '/admin'],
    'ignore'    => ['/api/auth','/api/user/change-pwd'],
    'attribute' => 'jwt',
    'secret'    => getenv('JWT_SECRET')
]));
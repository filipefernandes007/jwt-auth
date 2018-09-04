<?php

use Slim\Http\Request;
use Slim\Http\Response;
use App\Utils\JWTUtils;

// Routes

$app->get('/', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("'/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});

// API
$app->post('/api/auth', \App\Controller\ApiController::class . ':auth');
$app->get('/api/user/{id}', \App\Controller\ApiController::class . ':getUser');

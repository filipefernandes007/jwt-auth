<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->get('/', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("'/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});

$app->get('/admin', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("'/' route");

    // Render index view
    return $this->renderer->render($response, '/admin/index.phtml', $args);
});

$app->getContainer()->get('config')->setUpRoutes();

// API
//$app->post('/api/auth', \App\Controller\ApiController::class . ':auth');
//$app->get('/api/user/{id}', \App\Controller\ApiController::class . ':getUser');
//$app->post('/api/user/change-pwd/{id}', \App\Controller\ApiController::class . ':changePassword');

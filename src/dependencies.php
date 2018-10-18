<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function (\Slim\Container $c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function (\Slim\Container $c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

$container[\App\Repository\UserRepository::class] = function (\Slim\Container $c) {
    return new \App\Repository\UserRepository($c->get('settings')['db']);
};

$container[\App\Base\BaseApiController::class] = function (\Slim\Container $c) {
    return new \App\Base\BaseAPIController($c);
};

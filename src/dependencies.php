<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$app->getContainer()['renderer'] = function (\Slim\Container $c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$app->getContainer()['logger'] = function (\Slim\Container $c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

$app->getContainer()['config'] = function () use($app) {
    return new \App\Common\Config($app);
};

$app->getContainer()[\App\Base\BaseApiController::class] = function (\Slim\Container $c) {
    return new \App\Base\BaseAPIController($c);
};

(new \App\Common\Config($app))->setUpDependencyInjectionInAllPDORepositories();

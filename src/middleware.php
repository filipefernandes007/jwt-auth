<?php
// Application middleware

$app->add(new Tuupola\Middleware\JwtAuthentication([
    'path'      => ['/api', '/admin'],
    'ignore'    => ['/api/auth'],
    'attribute' => 'jwt',
    'logger'    => $app->getContainer()->get('logger'),
    'secret'    => getenv('JWT_SECRET')
]));

//Override the default Not Found Handler
$app->getContainer()['notAllowedHandler'] = function (\Slim\Container $c) {
    return function (\Slim\Http\Request $request, \Slim\Http\Response $response, $methods) use ($c) {
        /** @var \Monolog\Logger $logger */
        $logger = $c->get('logger');

        $errorOutput = ['Count' => '0', 'Results' => [], 'error' => 'Not-allowed. Please check, and submit again.', 'statusCode' => 405];

        \App\Common\LoggerResult::instance($logger)->result(\App\Common\LoggerResult::ERROR_TYPE, $request, $response, json_encode($errorOutput));

        return $c['response']
            ->withStatus(405)
            ->withHeader('Allow', implode(', ', $methods))
            ->withHeader('Content-type', 'text/html')
            ->write('Method must be one of: ' . implode(', ', $methods) . ':' . $request->getUri()->getPath());
    };
};

$app->getContainer()['notFoundHandler'] = function (\Slim\Container $c) {
    return function (\Slim\Http\Request $request, \Slim\Http\Response $response) use($c) {
        /** @var \Monolog\Logger $logger */
        $logger = $c->get('logger');

        $errorOutput = ['Count' => '0', 'Results' => [], 'error' => 'Invalid end-point: missing values or malformed url. Please check, and submit again.', 'statusCode' => \App\Base\BaseAPIController::STATUS_CODE_NOT_FOUND];

        \App\Common\LoggerResult::instance($logger)->result(\App\Common\LoggerResult::ERROR_TYPE, $request, $response, json_encode($errorOutput));

        return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withJson($errorOutput, \App\Base\BaseAPIController::STATUS_CODE_NOT_FOUND);
    };
};

$app->getContainer()['errorHandler'] = function (\Slim\Container $c) {
    return function (\Slim\Http\Request $request, \Slim\Http\Response $response, $exception) use ($c) {
        /** @var \Monolog\Logger $logger */
        $logger = $c->get('logger');

        $errorOutput = ['Count' => '0', 'Results' => [], 'error' => $exception->getMessage(), 'statusCode' => \App\Base\BaseAPIController::STATUS_CODE_SERVER_ERROR] ;

        \App\Common\LoggerResult::instance($logger)->result(\App\Common\LoggerResult::ERROR_TYPE, $request, $response, json_encode($errorOutput));

        return $response->withJson(['Count' => '0', 'Results' => [], 'error' => $exception->getMessage(), 'statusCode' => \App\Base\BaseAPIController::STATUS_CODE_SERVER_ERROR], \App\Base\BaseAPIController::STATUS_CODE_SERVER_ERROR);
    };
};
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

$app->post('/api/auth', function (Request $request, Response $response, array $args) use($app)  {
    $objectOnJsonRequest = json_decode($request->getBody());

    /** @var \App\Repository\UserRepository $repository */
    $repository = $app->getContainer()->get(\App\Repository\UserRepository::class);

    /** @var \App\Model\UserModel $user */
    $user = $repository->findByCredentials($objectOnJsonRequest->username, $objectOnJsonRequest->pwd);

    if ($user) {
        $jwtSecretKey = $app->getContainer()->get('settings')['jwt.secret_key'];
        $time         = time();

        $playLoad = array(
            'iat'  => $time,
            'exp'  => $time + (60 * 60),
            'data' => ['id' => $user->getId(), 'username' => $user->getUsername()]
        );

        $jwt = \Firebase\JWT\JWT::encode($playLoad, $jwtSecretKey);

        echo json_encode(['jwt' => $jwt]);

        return;
    }

    echo json_encode([]);
});

/**
 * Send as /api/get/{id} with Headers : Authorization: Bearer <jwt token>
 */
$app->get('/api/user/{id}', function (Request $request, Response $response, array $args) use($app)  {
    $jwtSecretKey = $app->getContainer()->get('settings')['jwt.secret_key'];
    $jwt          = $request->getHeader('Authorization')[0];

    try {
        $user    = new \App\Model\UserModel();
        $decoded = JWTUtils::decodeJwt($jwt, $jwtSecretKey);

        if ($decoded->data && $decoded->data->id) {
            /** @var \App\Repository\UserRepository $repository */
            $repository = $app->getContainer()->get(\App\Repository\UserRepository::class);

            /** @var \App\Model\UserModel $user */
            $user = $repository->find($decoded->data->id);
        }

        echo json_encode($user->toArray());
    } catch (Firebase\JWT\SignatureInvalidException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }

});


<?php
    /**
     * Filipe <filipefernandes007@gmail.com>
     */

    namespace App\Controller;

    use App\Base\BaseApiController;
    use App\Utils\JWTUtils;
    use Slim\Http\Request;
    use Slim\Http\Response;

    class ApiController extends BaseApiController
    {

        /**
         * @param Request  $request
         * @param Response $response
         * @param array    $args
         * @throws \Interop\Container\Exception\ContainerException|\Exception
         * @return Response
         */
        public function auth(Request $request, Response $response, array $args) : Response {
            $objectOnJsonRequest = json_decode($request->getBody());

            /** @var \App\Repository\UserRepository $repository */
            $repository = $this->container->get(\App\Repository\UserRepository::class);

            /** @var \App\Model\UserModel $user */
            $user = $repository->findByCredentials($objectOnJsonRequest->username, $objectOnJsonRequest->pwd);

            if ($user) {
                $jwtSecretKey = getenv('JWT_SECRET');;
                $time         = time();

                $playLoad = array(
                    'iat'  => $time,
                    'exp'  => $time + (60 * 60),
                    'data' => ['id' => $user->getId(), 'username' => $user->getUsername()]
                );

                $jwt = \Firebase\JWT\JWT::encode($playLoad, $jwtSecretKey);

                return $this->outputJson($response, ['jwt' => $jwt]);
            }

            return $this->outputJson($response, []);
        }

        /**
         * @param Request  $request
         * @param Response $response
         * @param array    $args
         * @return Response
         * @throws \Interop\Container\Exception\ContainerException
         * @throws \Exception
         */
        public function getUser(Request $request, Response $response, array $args) : Response {
            $jwtSecretKey = getenv('JWT_SECRET');
            $jwt          = $request->getHeader('Authorization')[0];

            try {
                $user    = new \App\Model\UserModel();
                $decoded = JWTUtils::decodeJwt($jwt, $jwtSecretKey);

                if ($decoded->data && $decoded->data->id) {
                    /** @var \App\Repository\UserRepository $repository */
                    $repository = $this->container->get(\App\Repository\UserRepository::class);

                    /** @var \App\Model\UserModel $user */
                    $user = $repository->find($decoded->data->id);
                }

                return $this->outputJson($response, $user->toArray());

            } catch (\Firebase\JWT\SignatureInvalidException $e) {
                return $this->outputJson($response, ['error' => $e->getMessage()]);
            }
        }
    }
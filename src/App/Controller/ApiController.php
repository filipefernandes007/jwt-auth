<?php
    /**
     * Filipe <filipefernandes007@gmail.com>
     */

    namespace App\Controller;

    use App\Base\BaseApiController;
    use App\Services\JWTService;
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

            /** @var \App\Model\UserModel|null $user */
            $user = $repository->findByCredentials($objectOnJsonRequest->username, $objectOnJsonRequest->pwd);

            if ($user !== null) {
                return $this->outputJson($response, ['jwt' => (new JWTService())->generate($user->getId(), ['id' => $user->getId(), 'username' => $user->getUsername()])]);
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
            try {
                $user = new \App\Model\UserModel();
                // or
                // $jwtSecretKey = getenv('JWT_SECRET');
                // $jwt          = $request->getHeader('Authorization')[0];
                // $decoded      = JWTService::decode($jwt, $jwtSecretKey);
                /** @var array $decoded */
                $decoded = $request->getAttribute('jwt'); // from middleware Tuupola\Middleware\JwtAuthentication

                /** @var integer $id */
                $id = (int) (new JWTService())->getSubId((object) $decoded);

                // remember that (int) null is 0
                if ($id !== 0) {
                    /** @var \App\Repository\UserRepository $repository */
                    $repository = $this->container->get(\App\Repository\UserRepository::class);

                    /** @var \App\Model\UserModel $user */
                    $user = $repository->find($id);
                }

                return $this->outputJson($response, $user->toArray());

            } catch (\Firebase\JWT\SignatureInvalidException $e) {
                return $this->outputJson($response, ['error' => $e->getMessage()]);
            }
        }

        /**
         * @param Request  $request
         * @param Response $response
         * @param array    $args
         * @return Response
         * @throws \Interop\Container\Exception\ContainerException
         * @throws \Exception
         */
        public function changePassword(Request $request, Response $response, array $args) : Response {
            $arrayResult = $request->getParsedBody();

            if (!isset($arrayResult['pwd'])) {
                return new Response(422, null, null);
            }

            $id   = (int) $request->getAttribute('id');
            $pwd  = $arrayResult['pwd'];
            $user = null;

            try {
                // remember that (int) null is 0
                if ($id !== 0) {
                    /** @var \App\Repository\UserRepository $repository */
                    $repository = $this->container->get(\App\Repository\UserRepository::class);

                    /** @var \App\Model\UserModel $user */
                    $user = $repository->find($id);

                    if ($user !== null) {
                        $user->setPassword(password_hash($pwd, PASSWORD_BCRYPT));
                        $repository->save($user);
                    }
                }

            } catch (\Exception $e) {
                return $this->outputJson($response, ['error' => $e->getMessage()]);
            }

            return $this->outputJson($response, $user->toArray());
        }
    }
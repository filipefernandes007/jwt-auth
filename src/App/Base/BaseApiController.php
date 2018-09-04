<?php
    /**
     * Filipe <filipefernandes007@gmail.com>
     */

    namespace App\Base;

    use Slim\Http\Response;

    class BaseApiController
    {
        const STATUS_CODE_OK           = 200;
        const STATUS_CODE_NOT_FOUND    = 404;
        const STATUS_CODE_SERVER_ERROR = 500;

        /** @var \Slim\Container */
        protected $container;

        /** @var \Monolog\Logger  */
        protected $logger;

        /**
         * BaseAPIController constructor.
         * @param \Slim\Container $container
         * @throws \Interop\Container\Exception\ContainerException
         */
        public function __construct(\Slim\Container $container) {
            $this->container = $container;
            $this->logger    = $container->get('logger');
        }

        /**
         * @param Response $response
         * @param          $body
         * @return Response
         * @throws \Exception
         */
        protected function outputJson(Response $response, $body) : Response
        {
            $encodedBody = json_encode($body);

            if (!$encodedBody) {
                throw new \Exception('Can not encode response body');
            }

            $response->getBody()->write($encodedBody);
            return $response->withHeader('Content-Type', 'application/json');
        }

    }
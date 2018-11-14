<?php
    /**
     * Created by PhpStorm.
     * User: Filipe <filipefernandes007@gmail.com>
     * Date: 18/10/2018
     * Time: 23:30
     */

    namespace App\Common;

    use Monolog\Logger;
    use Slim\Http\Request;
    use Slim\Http\Response;

    class LoggerResult {
        const ERROR_TYPE = 'error';
        const INFO_TYPE  = 'info';

        /** @var null|Logger */
        protected static $logger;

        protected static $instance = null;

        protected function __construct() {}
        protected function __clone() {}

        /**
         * @param Logger $logger
         * @return LoggerResult
         */
        public static function instance(Logger $logger) : LoggerResult {
                 if (self::$instance === null) {
                self::$instance = new static;
            }

            if (self::$logger === null) {
                self::$logger = $logger;
            }

            return self::$instance;
        }

        /**
         * @param string|null $type
         * @param Request     $request
         * @param Response    $response
         * @param             $result
         */
        public static function result(string $type = null,
            Request $request,
            Response $response,
            $result) {
            try {
                if ($type === self::ERROR_TYPE) {
                    switch ($request->getMethod()) {
                        case 'GET':
                            self::$logger->error(self::getFirstLoggerPart($request, $response) . ' | ' . $request->getUri()->getPath() . ' : ' . $result);
                            break;
                        case 'POST':
                            self::$logger->error(self::getFirstLoggerPart($request, $response) . ' [' . json_encode((array) $request->getParsedBody()) . '] | ' .  $request->getUri()->getPath() . ' : ' . $result);
                            break;
                    }
                }

                if ($type === self::INFO_TYPE) {
                    switch ($request->getMethod()) {
                        case 'GET':
                            self::$logger->info(self::getFirstLoggerPart($request, $response) . ' | ' . $request->getUri()->getPath() . ' : ' . $result);
                            break;
                        case 'POST':
                            self::$logger->info(self::getFirstLoggerPart($request, $response) . ' [' . json_encode((array) $request->getParsedBody()) . '] | ' .  $request->getUri()->getPath() . ' : ' . $result);
                            break;
                    }
                }
            } catch (\Exception $e) {
                die($e->getMessage());
            }
        }

        /**
         * @param Request  $request
         * @param Response $response
         * @return string
         */
        private static function getFirstLoggerPart(Request $request, Response $response) : string {
            return $request->getMethod();
        }
    }
<?php
    /**
     * Created by PhpStorm.
     * User: Filipe <filipefernandes007@gmail.com>
     * Date: 18/10/2018
     * Time: 15:20
     */

    namespace App\Common;

    /**
     * Class Config
     * @package App\Common
     */
    class Config
    {
        /** @var \Slim\App */
        protected $app;

        /**
         * Config constructor.
         * @param \Slim\App $app
         */
        public function __construct(\Slim\App $app)
        {
            $this->app = $app;
        }

        public function setUpRoutes() : void {
            $yaml   = file_get_contents(ROOT . 'config/routes.yaml');
            $routes = \Spyc::YAMLLoad($yaml);

            foreach ($routes as $controllerName => $controller) {
                foreach ($controller as $routes) {
                    foreach ($routes as $routeName => $route) {
                        switch ($route['method']) {
                            case 'GET':
                                $this->app->get($routeName, '\\App\\Controller\\' . $controllerName . ':' . $route['action']);
                                break;

                            case 'POST':
                                $this->app->post($routeName, '\\App\\Controller\\' . $controllerName . ':' . $route['action']);
                                break;

                            case 'PUT':
                                $this->app->put($routeName, '\\App\\Controller\\' . $controllerName . ':' . $route['action']);
                                break;

                            case 'DELETE':
                                $this->app->delete($routeName, '\\App\\Controller\\' . $controllerName . ':' . $route['action']);
                                break;

                        }
                    }
                }
            }
        }
    }
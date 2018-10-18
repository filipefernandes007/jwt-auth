<?php
    /**
     * Created by PhpStorm.
     * User: Filipe <filipefernandes007@gmail.com>
     * Date: 18/10/2018
     * Time: 15:20
     */

    namespace App\Common;

    use App\Base\BasePDORepository;

    /**
     * Class Config
     * @package App\Common
     */
    class Config
    {
        /** @var \Slim\App */
        protected $app;

        /** @var \Slim\Container */
        protected $container;

        /**
         * Config constructor.
         * @param \Slim\App       $app
         */
        public function __construct(\Slim\App $app)
        {
            $this->app       = $app;
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

        // TODO this don't work for composer test
        public function setUpDependencyInjectionInAllPDORepositories(&$c) : void
        {
            foreach (glob(APP . '/Repository/*.php') as $file) {
                $classnName = explode('src/', $file);
                $class      = str_replace('.php', '', str_ireplace('/', '\\', $classnName[1]));

                if (is_subclass_of('\\' . $class, BasePDORepository::class)) {
                    $c[$class] = function (\Slim\Container $c) use ($class) {
                        return new $class($c->get('settings')['db']);
                    };
                }
            }
        }
    }
<?php
    /**
     * Filipe <filipefernandes007@gmail.com>
     */

    namespace Tests\Functional;


    use App\Services\JWTService;

    class ApiTest extends BaseTestCase
    {
        const URL = 'http://localhost:8090';

        protected static $jwt;

        protected function setUp() : void
        {
            echo "\n" . $this->getName();
        }

        public function testApiAuth() : void
        {
            $client  = new \GuzzleHttp\Client();
            $request = new \GuzzleHttp\Psr7\Request('POST',
                                                    self::URL . '/api/auth',
                                                    ['Content-Type' => 'application/json'],
                                                    json_encode(['username' => 'filipefernandes007',
                                                                 'pwd'      => '123']));

            $promise = $client->sendAsync($request)->then(function (\GuzzleHttp\Psr7\Response $response) {
                $result       = json_decode($response->getBody()->getContents());
                self::$jwt    = $result->jwt;
                $jwtSecretKey = $this->jwtSecret;

                $this->assertEquals(200, $response->getStatusCode());
                $this->assertObjectHasAttribute('jwt', $result);
                $this->assertTrue(JWTService::isSignatureValid(self::$jwt, $jwtSecretKey));
            });

            $promise->wait();
        }

        public function testRequestWithJwt() : void
        {
            $client  = new \GuzzleHttp\Client();
            $request = new \GuzzleHttp\Psr7\Request('GET',
                                                    self::URL . '/api/user/1',
                                                    ['Content-Type'  => 'application/json',
                                                     'Authorization' => 'Bearer ' . self::$jwt]);

            $promise = $client->sendAsync($request)->then(function (\GuzzleHttp\Psr7\Response $response) use($request) {
                $result = json_decode($response->getBody()->getContents());

                $this->assertEquals(200, $response->getStatusCode());
                $this->assertEquals(1, $result->id);
                $this->assertEquals('filipefernandes007', $result->username);
            });

            $promise->wait();
        }

        /**
         * @throws \GuzzleHttp\Exception\GuzzleException
         */
        public function testFailJwtWasChanged() : void
        {
            $jwtChanged = substr_replace(self::$jwt,'x',-1);

            $client  = new \GuzzleHttp\Client();
            $request = new \GuzzleHttp\Psr7\Request('GET',
                                                    self::URL . '/api/user/1',
                                                    ['Content-Type'  => 'application/json',
                                                     'Authorization' => 'Bearer ' . $jwtChanged]);

            $promise = $client->sendAsync($request)->then(function (\GuzzleHttp\Psr7\Response $response) {
                $result = json_decode($response->getBody()->getContents());

                $this->assertEquals(200, $response->getStatusCode());
                $this->assertEquals(1, $result->id);
                $this->assertEquals('filipefernandes007', $result->username);
            });

            try {
                $promise->wait();
            } catch (\GuzzleHttp\Exception\ServerException $e) {
                $this->assertEquals(401, $e->getResponse()->getStatusCode());
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                $this->assertEquals(401, $e->getResponse()->getStatusCode());
            }
        }

        public function testFailJwtExpired() : void
        {
            $jwt = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1MzU5OTYzMDYsImV4cCI6MTUzNTk5OTkwNiwiZGF0YSI6eyJpZCI6MSwidXNlcm5hbWUiOiJmaWxpcGVmZXJuYW5kZXMwMDcifX0.BmCWwx1QRzhXBFGCC3hPBkhogtMIdizuOoyo6DFmYKw';

            $client  = new \GuzzleHttp\Client();
            $request = new \GuzzleHttp\Psr7\Request('GET',
                                                    self::URL . '/api/user/1',
                                                    ['Content-Type'  => 'application/json',
                                                     'Authorization' => 'Bearer ' . $jwt]);

            $promise = $client->sendAsync($request)->then(function (\GuzzleHttp\Psr7\Response $response) {
                $result = json_decode($response->getBody()->getContents());

                $this->assertEquals(200, $response->getStatusCode());
                $this->assertEquals(1, $result->id);
                $this->assertEquals('filipefernandes007', $result->username);
            });

            try {
                $promise->wait();
            } catch (\GuzzleHttp\Exception\ServerException $e) {
                $this->assertEquals(401, $e->getResponse()->getStatusCode());
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                $this->assertEquals(401, $e->getResponse()->getStatusCode());
            }
        }

    }
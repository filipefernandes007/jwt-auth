
#JWT Auth - _Small example on how to use JWT to interact with your API_ 

#Requirements

* PHP 7.1
* [Sqlite3](https://www.sqlite.org/index.html)
* [composer - Dependency Manager for PHP](https://getcomposer.org/download/) 
* [ext-pdo-sqlite](http://php.net/manual/en/ref.pdo-sqlite.php)

#Install

```bash
$ git clone https://github.com/filipefernandes007/jwt-auth.git
$ cd jwt-auth
$ composer self-update
$ composer install
```

#Run application

```bash
$ composer start
```

#Routes

POST /api/auth : {"jwt":"some valid jwt"}
```
curl -d '{"username":"filipefernandes007", "pwd":"123"}' -H "Content-Type: application/json" -X POST http://localhost:8090/api/auth
```

GET  /api/user/<id> : with <jwt> from /api/auth 
```
curl -X GET http://localhost:8090/api/user/1 \
-H "Content-Type: application/json" \
-H "Authorization: Bearer <jwt>"

curl -X GET http://localhost:8090/api/user/1 \
-H "Content-Type: application/json" \
-H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJmaWxpcGVmZXJuYW5kZXMwMDctand0fDEiLCJpc3MiOiJcL2F1dGgiLCJpYXQiOjE1Mzk4NzQ1MjUsImV4cCI6MTUzOTg3ODEyNSwiZGF0YSI6eyJpZCI6MSwidXNlcm5hbWUiOiJmaWxpcGVmZXJuYW5kZXMwMDcifX0.3conBhpJ9eX3mup3tptjpW_OdL70uB-zUEUyF4haaRkfbBAEDNF41jLd6fmd2W_7EqUq6inX5EnELnQtXeqx8g"
```

POST /api/user/change-pwd/<id> :
```
curl -X POST http://localhost:8090/api/user/change-pwd/<id> \
-d '{"pwd":"your new password"}' \
-H "Content-Type: application/json" \
-H "Authorization: Bearer <jwt>"

curl -X POST http://localhost:8090/api/user/change-pwd/1 \
-d '{"pwd":"123"}' \
-H "Content-Type: application/json" \
-H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJmaWxpcGVmZXJuYW5kZXMwMDctand0fDEiLCJpc3MiOiJcL2F1dGgiLCJpYXQiOjE1Mzk4NzQ1MjUsImV4cCI6MTUzOTg3ODEyNSwiZGF0YSI6eyJpZCI6MSwidXNlcm5hbWUiOiJmaWxpcGVmZXJuYW5kZXMwMDcifX0.3conBhpJ9eX3mup3tptjpW_OdL70uB-zUEUyF4haaRkfbBAEDNF41jLd6fmd2W_7EqUq6inX5EnELnQtXeqx8g"
```

[Access application here](http://localhost:8090) 

#Functional tests

You can run tests with bash command ``` composer test ```

Enjoy!

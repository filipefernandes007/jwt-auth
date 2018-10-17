
#JWT Auth - _Small example on how to use JWT to interact with your API_ 

This project allows you to shrink your links. It is a Slim PHP 3.x based application. Feel free to test.

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
-H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJmaWxpcGVmZXJuYW5kZXMwMDctand0fDEiLCJpc3MiOiJcL2F1dGgiLCJpYXQiOjE1Mzk4MDgyNDAsImV4cCI6MTUzOTgxMTg0MCwiZGF0YSI6eyJpZCI6MSwidXNlcm5hbWUiOiJmaWxpcGVmZXJuYW5kZXMwMDcifX0.JFZR1VTBLE36sm5a5vd8otwkLlv8FXqqmlAuDlCB-fUfT9-d5QX7M8Z35lfw21ZeozHoKqGbe_NNiD11WKGvVA"
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
-H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJmaWxpcGVmZXJuYW5kZXMwMDctand0fDEiLCJpc3MiOiJcL2F1dGgiLCJpYXQiOjE1Mzk4MDgyNDAsImV4cCI6MTUzOTgxMTg0MCwiZGF0YSI6eyJpZCI6MSwidXNlcm5hbWUiOiJmaWxpcGVmZXJuYW5kZXMwMDcifX0.JFZR1VTBLE36sm5a5vd8otwkLlv8FXqqmlAuDlCB-fUfT9-d5QX7M8Z35lfw21ZeozHoKqGbe_NNiD11WKGvVA"
```

[Access application here](http://localhost:8090) 

#Functional tests

You can run tests with bash command ``` composer test ```

Enjoy!
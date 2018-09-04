
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

Now start the application (it does not start automatically, but if you want, uncomment the `` php bin/console server:run 192.168.33.89:8000 `` command in your Vagrant file to do so next time you 'reload' Vagrant):  

```bash
$ composer start
```

[Access application here](http://localhost:8090) 

#Unit tests

You can run tests with bash command ``` composer test ```

Enjoy!
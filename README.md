# Example of Register/Login users with PHP 

This is a example of how you can use PHP in a good way to create a SignIn/SignUp system, using the SOILD principles, clean code and Testing. 



## Design Decisions


### Dependencies

I use composer as the tool for the dependencies, you can hightligth the packages as follow

```
"doctrine/dbal": "2.3.4",
"silex/silex": "~1.3",
"mockery/mockery": "*",
"symfony/validator": "2.4@stable",
"twig/twig": "~1.0",
"robmorgan/phinx": "*",
```

* **Doctrine DBAL**

I don't like ORM, too much overhead, but I like to use Doctrine DBAL for handle my MySql connections, it's easy and clean and you already can use the Query Builder. 

Easy to test, and support Master/Slave connections, and others features. 


* **Silex**

Silex is my favorite framework, small and you only make reference to it in the frontcontroller, web/index.php.

* **Mockery**

Mockery for mock object instead of PHPUnit for mocking object

* **Validator**

Symfony/Validator is the best tool for validate values and form, easy to configurate and object oriented

* **Twig**

The best PHP engine template

* **Phinx**

Phinx is the PHP version of rake for SQL changes, it's clean and easy to use. 


### Directories

* documentRoot

```
bin                build              composer.json      composer.lock
docker-compose.yml migrations         phinx.yml          phpunit.xml
src                tests              var                vendor             
web
```

web/ is the entry point to the applications your apache virtual host is going to read just this folder. 

I configurate the application to work with Docker, you are going to find a docker-composer.yml, var/ and bin/ just running docker-composer up -d must be enough. 

Phinx need phinx.xml for the configuration and migrations/ for create the migrations files.

composer.json / composer.lock for Composer. 

tests/ I like to work with ATDD/TDD but to avoid taking more the time I want to expend on this the only Unit Test I made was Signup, and partially I don't even test the Validator part. Sorry I'm lazy sometimes.

build/ this folder is created by the phpunit.xml for reporting only, can be used by Jenkins for continues integrations. 

And the whole code I made is in the src/ folder

```
src
├── Config
│   └── credentials.php
├── Controllers
│   ├── Authentication.php
│   └── Users.php
├── Models
│   ├── Users.php
│   └── Validators
│       ├── Authentication.php
│       └── Users.php
└── Views
    ├── footer.twig
    ├── header.twig  
    └── users 
        ├── login.twig
        ├── search.twig 
        └── signup.twig 
``` 

I try to keep the things simple as possbile. 


## TODO

* Create Unit Test for the rest of the application
* Add Acceptance Tests (Behat maybe, I prefer watir or ruby)
ToDo & Co
========

Base du projet #8 : Améliorez un projet existant

https://openclassrooms.com/projects/ameliorer-un-projet-existant-1

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/69b2b40165e64ccdaa074b8fc61e0934)](https://app.codacy.com/gh/TonyWTillet/todolist/dashboard?utm_source=gh&utm_medium=referral&utm_content=&utm_campaign=Badge_grade)

<a href="https://codeclimate.com/github/TonyWTillet/todolist/maintainability"><img src="https://api.codeclimate.com/v1/badges/660ecc5dfa4888614a73/maintainability" /></a>

<a href="https://codeclimate.com/github/TonyWTillet/todolist/test_coverage"><img src="https://api.codeclimate.com/v1/badges/660ecc5dfa4888614a73/test_coverage" /></a>

## Pre-requisites
Link to doc technical requirements
Symfony Local Web Server or Configure your local server MAMP, WAMP
PHP 8.3 or more
MySQL 5.7 or more

## Installation
1. Copy repository
```
git clone https://github.com/jucarre/TodoList.git
```

2. Configure BDD connect on .env file
3. Install the dependencies
```
composer install
```

4. Create database
```
bin/console doctrine:database:create
```

5. Migrate database table
```
bin/console doctrine:schema:create
```

6. Load fixtures in database
```
bin/console doctrine:fixtures:load
```

7. Start server
```
symfony server:start
```

## Tests
```
bin/phpunit
or
bin/phpunit --coverage-html docs/test-coverage
```
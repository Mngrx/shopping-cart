# Shopping cart application

## About

It is an API to serve an Shopping Cart service and a CRUD for Products.

## How to run

### Sail

This project is using [Laravel Sail](https://laravel.com/docs/9.x/sail). It helps create a docker development environment, making it easy to run the application locally and run secondary services such as MySQL and Redis.

### Steps

The project was built using PHP 8.2.

First of all, you need to have [composer](https://getcomposer.org/) in your machine. Then, run:

```sh
cp .env.example .env
composer install
```

After that, you can use the sail's script (it is in `./vendor/bin/sail`) to run the application.

```sh
./vendor/bin/sail up -d
```

Then, you need to generate the applcation key and run the migrations and seed the database
```sh
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan db:seed
```

Finally, you can interact with the API. Access: [http://localhost:8880](http://localhost:8880).

## Docs

There is some diagrams in [docs folder](https://github.com/Mngrx/shopping-cart/tree/main/docs). 

### OpenAPI documentation

With the application running, it is possible to access the [OpenAPI documention](http://localhost:8880/api/documentation).

## Testing with Postman

With the application runnig, you can use Postman to load the collection located in `./postman/app_collection.json`.

### Auth

Those requests are related to authentication. There are two request to simulate a login, one for admin login and other to a regular user login. The difference between then is that the admin can create, update and delete products. For all the others endpoints, they have the same authorizations.


## TODO

- Restructure the tests that interact with Redis to have more concise assertions in data inserts and retrieval operations.
- Create a complete authentication system.
- Improve validations during the `Shopping Cart Checkout`. For example, validate if a product still active.
- Improve the way the system deal with avaibility of databases services (MySQL and Redis). Currently, it is just returning 500 error for the user.
- Create adapters to manipulate response data. For example, to hide `timestamp` fields from responses.
- Dockerization for production purposes.
- Use some tool like [Larastan](https://github.com/nunomaduro/larastan) or [PHP metrics](https://www.phpmetrics.org/), running automatically to keep the code quality high.
- Improve OpenAPI documentation to be interactive and have all possibles requests examples.
- Create a pipeline to integrate all quality gates: unit tests, integration tests, audit, and others.
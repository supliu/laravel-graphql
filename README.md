<p align="center">
  <img src="assets/logo.png" width="150">
</p>

<p align="center">
  <a href="https://packagist.org/packages/supliu/laravel-graphql"><img src="https://poser.pugx.org/supliu/laravel-graphql/d/total.svg" alt="Total Downloads"></a>
  <a href="https://packagist.org/packages/supliu/laravel-graphql"><img src="https://poser.pugx.org/supliu/laravel-graphql/v/stable.svg" alt="Latest Stable Version"></a>
</p>

# Laravel GraphQL

The objective of this project is to facilitate the integration of the <a href="https://github.com/webonyx/graphql-php">webonyx/graphql-php</a> library with the Laravel Framework.

## How to install

Use composer to install this package

```
composer require supliu/laravel-graphql
```

Add Service Provider in `config/app.php`

```php
'providers' => [
    /*
     * Package Service Providers...
     */
    \Supliu\LaravelGraphQL\ServiceProvider::class,
],
```

Execute a publish with artisan command:

```
php artisan vendor:publish --provider="\Supliu\LaravelGraphQL\ServiceProvider::class"
```

## How to use

You must declare your <a href="https://graphql.org/learn/queries/">Query</a> and <a href="https://graphql.org/learn/queries/#mutations">Mutation</a> classes so that GraphQL can read.

### Query

Below is an example of a Query class that returns the data of a Star Wars hero:

```php
<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Supliu\LaravelGraphQL\Query;

class HeroQuery extends Query
{
    /**
     * @return Type
     */
    protected function typeResult(): Type
    {
        return new ObjectType([
            'name' => 'HeroQueryResult',
            'fields' => [
                'name' => Type::string()
            ]
        ]);
    }

    /**
     * @return mixed
     */
    protected function resolve($root, $args, $context, $info)
    {
        return [
            'name' => 'R2-D2'
        ];
    }
}
```

## License

The Laravel GraphQL is open-sourced project licensed under the [MIT license](https://opensource.org/licenses/MIT).

<?php

namespace Supliu\LaravelGraphQL;

use GraphQL\GraphQL;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/graphql.php', 'graphql'
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /*
         * Load views
         */
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-graphql');

        /*
         * Publish config
         */
        $this->publishes([
            __DIR__.'/../config/graphql.php' => config_path('graphql.php')
        ], 'config');

        /*
         * Publish view
         */
        $this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/laravel-graphql'),
        ], 'views');

        /*
         * Add routers
         */
        $router = $this->app['router'];

        $router->get('/graphql', 'Supliu\LaravelGraphQL\GraphQLController@index');
        $router->post('/graphql', 'Supliu\LaravelGraphQL\GraphQLController@executeQuery');
    }

    protected function bootPublishes()
    {

    }
}
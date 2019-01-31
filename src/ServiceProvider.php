<?php

namespace Supliu\LaravelGraphQL;

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

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $router = $this->app['router'];

        $router->post('/graphql', function(){

            return response()->json([
                'data' => [
                    'hero' => [
                        'name' => 'R2-D2'
                    ]
                ]
            ]);
        });
    }
}
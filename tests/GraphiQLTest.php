<?php

namespace Supliu\LaravelGraphQL\Tests;

use Orchestra\Testbench\TestCase;

class GraphiQLTest extends TestCase
{
    /**
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Supliu\LaravelGraphQL\ServiceProvider::class
        ];
    }

    /**
     * @test Query Hero
     */
    public function testQueryHero(): void
    {
        $crawler = $this->call('GET', '/graphql');

        $crawler->assertViewIs('laravel-graphql::graphiql');
    }
}
<?php

namespace Supliu\LaravelGraphQL\Tests;

use Orchestra\Testbench\TestCase;

class HeroQueryTest extends TestCase
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
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('graphql.schemas.default.queries', [
            'hero' => \Supliu\LaravelGraphQL\Tests\Queries\HeroQuery::class
        ]);
    }

    /**
     * @test Query Hero
     */
    public function testQueryHero(): void
    {
        $query = '
            query HeroNameQuery {
              hero {
                name
              }
            }
        ';

        $crawler = $this->call('POST', '/graphql', ['query' => $query]);

        $crawler->assertJson([
            'data' => [
                'hero' => [
                    'name' => 'R2-D2'
                ]
            ]
        ]);
    }
}
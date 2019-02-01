<?php

namespace Supliu\LaravelGraphQL\Tests;

use Orchestra\Testbench\TestCase;

class HeroMutationTest extends TestCase
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
        $app['config']->set('graphql.schemas.default.mutations', [
            'hero' => \Supliu\LaravelGraphQL\Tests\Mutations\HeroMutation::class
        ]);
    }

    /**
     * @test Query Hero
     */
    public function testQueryHero(): void
    {
        $query = '
            mutation {
              hero (id: 1){
                hero_id,
                error,
                message
              }
            }
        ';

        $crawler = $this->call('POST', '/graphql', ['query' => $query]);

        $crawler->assertJson([
            'data' => [
                'hero' => [
                    'hero_id' => '1',
                    'error' => false,
                    'message' => 'Updated!'
                ]
            ]
        ]);
    }
}
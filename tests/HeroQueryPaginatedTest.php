<?php

namespace Supliu\LaravelGraphQL\Tests;

use Orchestra\Testbench\TestCase;

class HeroQueryPaginatedTest extends TestCase
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
            'heros' => \Supliu\LaravelGraphQL\Tests\Queries\HeroQueryPaginated::class
        ]);
    }

    /**
     * @test Query Hero
     */
    public function testQueryHero(): void
    {
        $query = '
            query HeroNameQuery {
              heros {
                total
                current_page,
                per_page,
                last_page,
                data {
                    name
                }
              }
            }
        ';

        $crawler = $this->call('POST', '/graphql', ['query' => $query]);

        $crawler->assertJson( [
            'data' => [
                'heros' => [
                    'total' => 1,
                    'current_page' => 1,
                    'per_page' => 15,
                    'last_page' => 1,
                    'data' => [
                        [
                            'name' => 'R2-D2'
                        ]
                    ]
                ]
            ]
        ]);
    }
}
<?php

namespace Supliu\LaravelGraphQL;

use GraphQL\GraphQL;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class GraphQLController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('laravel-graphql::graphiql');
    }

    /**
     * @param Request $request
     * @param GraphQL $graphQL
     * @param SchemaFactory $schemaFactory
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function executeQuery(Request $request, GraphQL $graphQL, SchemaFactory $schemaFactory)
    {
        /*
         * Get schema
         */
        $schema = $request->get('schema', 'default');

        /*
         * Get query
         */
        $query = $request->get('query');

        /*
         * Get variables
         */
        $variables = $request->get('variables');

        /*
         * Check operations
         */
        if($request->has('operations')) {

            $operations = json_decode($request->get('operations'), true);

            $query = $operations['query'];
            $variables = $operations['variables'];
        }

        /*
         * Execute Query
         */
        $result = $graphQL->executeQuery($schemaFactory->create($schema), $query, null, null, $variables);

        /*
         * Show error
         */
        if(count($result->errors) > 0){

            foreach ($result->errors as $error)
                if(!is_null($error->getPrevious()))
                    report($error->getPrevious());

            return response()->json(['errors' => $result->errors]);
        }

        /*
         * To json
         */
        return response()->json($result->toArray());
    }
}
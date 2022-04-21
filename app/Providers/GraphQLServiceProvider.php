<?php

namespace App\Providers;

use App\Helpers\GraphQLNullValueHandler;
use ArrayAccess;
use Closure;
use GraphQL\Executor\Executor;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class GraphQLServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        self::customGraphQLResolver();
    }

    /**
     * Very nice, a custom resolver!
     *
     * @return void
     */
    private static function customGraphQLResolver() {
        Executor::setDefaultFieldResolver(function ($objectValue, $args, $contextValue, ResolveInfo $info) {
            $fieldName  = $info->fieldName;
            $property   = null;
            $returnType = strtolower($info->returnType->name);

            // handle null values
            if (isset($returnType)) {
                if ($objectValue[$fieldName] === null) {

                    // lets log it
                    $logData = [
                        'object' => $objectValue->toArray(),
                        'null' => [
                            'field' => $fieldName,
                            'type' => $returnType,
                        ]
                    ];
                    Log::channel('gql')->warning('GraphQL returned null: ' . serialize($logData));

                    // ...maybe send and email too?

                    return GraphQLNullValueHandler::handle($returnType);
                }
            }

            if (is_array($objectValue) || $objectValue instanceof ArrayAccess) {
                if (isset($objectValue[$fieldName])) {
                    $property = $objectValue[$fieldName];
                }
            } elseif (is_object($objectValue)) {
                if (isset($objectValue->{$fieldName})) {
                    $property = $objectValue->{$fieldName};
                }
            }
    
            return $property instanceof Closure
                ? $property($objectValue, $args, $contextValue, $info)
                : $property;
        });
    }
}

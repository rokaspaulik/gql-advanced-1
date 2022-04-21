<?php

namespace App\GraphQL\Directives;

use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Schema\Values\FieldValue;
use Nuwave\Lighthouse\Support\Contracts\FieldMiddleware;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class SomeMiddlewareDirective extends BaseDirective implements FieldMiddleware
{
    public static function definition(): string
    {
        return /** @lang GraphQL */ <<<'GRAPHQL'
directive @someMiddleware on FIELD_DEFINITION
GRAPHQL;
    }

    /**
     * Wrap around the final field resolver.
     *
     * @param  \Nuwave\Lighthouse\Schema\Values\FieldValue  $fieldValue
     * @param  \Closure  $next
     * @return \Nuwave\Lighthouse\Schema\Values\FieldValue
     */
    public function handleField(FieldValue $fieldValue, Closure $next)
    {
        $resolver = $fieldValue->getResolver();

        // If you have any work to do that does not require the resolver arguments, do it here.
        // This code is executed only once per field, whereas the resolver can be called often.

        $fieldValue->setResolver(function ($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo) use ($resolver) {
            // Do something before the resolver, e.g. validate $args, check authentication

            // Call the actual resolver
            $result = $resolver($root, $args, $context, $resolveInfo);

            // Do something with the result, e.g. transform some fields

            // if string return empty
            $result = match(gettype($result)) {
                'string' => '--- FIELD INTERCEPTED BY MIDDLEWARE ---',
                default => $result
            };

            return $result;
        });

        // Keep the chain of adding field middleware going by calling the next handler.
        // Calling this before or after ->setResolver() allows you to control the
        // order in which middleware is wrapped around the field.
        return $next($fieldValue);
    }
}

<?php

namespace Database\Factories;

use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $theScaryEvilJSON = json_encode([
            'someval' => 'woah',
            'wow' => [
                'this stuff',
                'really',
                'works'
            ],
            'and' => [
                'it' => 'is really amazing',
                'some' => [
                    'deep',
                    'nested',
                    'stuff'
                ]
            ]
        ]);

        $theNullJson = null;

        return [
            'title' => $this->faker->word(),
            'author_id' => Author::all()->random()->id,
            // 'description' => Str::random(200),
            // 'description' => $theScaryEvilJSON,
            'description' => $theNullJson,
            'price' => $this->faker->numberBetween(400, 10000),
            // 'rating' => $this->faker->numberBetween(20, 100),
            'rating' => null,
            'release_year' => now()->year,
        ];
    }
}

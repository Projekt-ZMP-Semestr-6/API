<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Game>
 */
class GameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->domainName(),
            'appid' => $this->faker->numberBetween(0, 300000),
            'last_modified' => $this->faker->unixTime(),
        ];
    }

    public function batman()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'LEGO® Batman™: The Videogame',
                'appid' => 21000,
                'last_modified' => '1573509038',
            ];
        });
    }
}

<?php

namespace Database\Factories;

use App\Models\Click;
use App\Models\Link;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Click>
 */
class ClickFactory extends Factory
{
    public function definition(): array
    {
        return [
            'link_id' => Link::factory(),
            'ip_address' => $this->faker->ipv4(),
        ];
    }
}

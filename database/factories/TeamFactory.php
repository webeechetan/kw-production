<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Team;
use App\Helpers\Helper;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Team>
 */
class TeamFactory extends Factory
{

    public $teams = ['Tech Team','Media Team','CS Team','HR Team'];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'org_id' => '1',
            'name' => $this->faker->unique()->randomElement($this->teams),
            'description' => $this->faker->text(),
            'image' => null
        ];
    }
}

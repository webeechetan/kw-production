<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Client;
use App\Models\Organization;
use App\Helpers\Helper;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public $clients = ['Acma', 'Birla', 'Cipla', 'Dabur', 'Emami', 'Fevicol', 'Godrej', 'Havells', 'ITC', 'Jindal', 'Kajaria', 'L&T', 'Marico', 'Nestle', 'Oberoi', 'Patanjali', 'Quikr', 'Reliance', 'SBI', 'Tata', 'UltraTech', 'Vedanta', 'Wipro', 'Xiaomi', 'Yamaha', 'Zomato'];

    public function definition(): array
    {
        return [
            'org_id' => '1',
            'name' => $this->faker->unique()->randomElement($this->clients),
            'description' => $this->faker->text(),
        ];
    }
}

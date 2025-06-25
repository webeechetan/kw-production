<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Project;
use App\Helpers\Helper;
use App\Models\Client;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    // public $clients = ['Acma', 'Birla', 'Cipla', 'Dabur', 'Emami', 'Fevicol', 'Godrej', 'Havells', 'ITC', 'Jindal', 'Kajaria', 'L&T', 'Marico', 'Nestle', 'Oberoi', 'Patanjali', 'Quikr', 'Reliance', 'SBI', 'Tata', 'UltraTech', 'Vedanta', 'Wipro', 'Xiaomi', 'Yamaha', 'Zomato'];
    
    // create projects like if client is acma then project will be acma website , acma mobile app , acma branding , acma marketing , acma seo , acma social media , acma email marketing

    public $projects = [
        'Website', 'Mobile App', 'Branding', 'Marketing', 'SEO', 'Social Media', 'Email Marketing', 'Content Marketing', 'PPC', 'Affiliate Marketing', 'Influencer Marketing', 'Public Relations', 'Event Marketing', 'Guerrilla Marketing', 'Viral Marketing', 'Search Engine Marketing', 'Search Engine Optimization', 'Social Media Marketing', 'Content Marketing', 'Email Marketing', 'Affiliate Marketing', 'Influencer Marketing', 'Public Relations', 'Event Marketing', 'Guerrilla Marketing', 'Viral Marketing', 'Search Engine Marketing', 'Search Engine Optimization', 'Social Media Marketing', 'Content Marketing', 'Email Marketing', 'Affiliate Marketing', 'Influencer Marketing', 'Public Relations', 'Event Marketing', 'Guerrilla Marketing', 'Viral Marketing', 'Search Engine Marketing', 'Search Engine Optimization', 'Social Media Marketing', 'Content Marketing', 'Email Marketing', 'Affiliate Marketing', 'Influencer Marketing', 'Public Relations', 'Event Marketing', 'Guerrilla Marketing', 'Viral Marketing', 'Search Engine Marketing', 'Search Engine Optimization', 'Social Media Marketing', 'Content Marketing', 'Email Marketing', 'Affiliate Marketing', 'Influencer Marketing', 'Public Relations', 'Event Marketing', 'Guerrilla Marketing', 'Viral Marketing', 'Search Engine Marketing', 'Search Engine Optimization', 'Social Media Marketing', 'Content Marketing', 'Email Marketing', 'Affiliate Marketing', 'Influencer Marketing', 'Public Relations', 'Event Marketing', 'Guerrilla Marketing', 'Viral Marketing', 'Search Engine Marketing', 'Search Engine Optimization', 'Social Media Marketing', 'Content Marketing', 'Email Marketing', 'Affiliate Marketing', 'Influencer Marketing', 'Public Relations', 'Event Marketing', 'Guerrilla Marketing', 'Viral Marketing', 'Search Engine Marketing', 'Search Engine Optimization', 'Social Media Marketing', 'Content Marketing', 'Email Marketing', 'Affiliate Marketing', 'Influencer Marketing', 'Public Relations', 'Event Marketing', 'Guerrilla Marketing', 'Viral Marketing', 'Search Engine Marketing', 'Search Engine Optimization', 'Social Media Marketing', 'Content Marketing', 'Email Marketing', 'Affiliate Marketing', 'Influencer Marketing', 'Public Relations', 'Event Marketing', 'Guerrilla Marketing', 'Viral Marketing', 'Search Engine Marketing', 'Search Engine Optimization', 'Social Media Marketing', 'Content Marketing', 'Email Marketing', 'Affiliate Marketing', 'Influencer Marketing', 'Public Relations'
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'org_id' => '1', 
            'client_id' => Client::factory(),
            'name' => $this->faker->unique()->randomElement($this->projects),
            'description' => $this->faker->text(),
            'image' => null
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Task;
use App\Models\Project;
use App\Models\Team;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = ['pending', 'completed','in_progress','in_review'];

        return [
            'org_id' => '1',
            'project_id' => Project::factory(),
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'status' => $status[array_rand($status)],
            'due_date' => $this->faker->date(),
            'team_id' => Team::factory(),
            'assigned_by' => User::factory(),
        ];
    }
}

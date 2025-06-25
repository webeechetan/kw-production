<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Organization;
use App\Models\User;
use App\Models\Team;
use App\Models\Client;
use App\Models\Project;
use App\Models\Task;
use App\Models\Attachment;
use App\Models\Comment;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Hash;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $org = new Organization();
        // $org->name = 'Webeesocial';
        // $org->email = 'wbs@gmail.com';
        // $org->password = Hash::make('123456');
        // $org->image = Helper::createAvatar($org->name,'users');
        // $org->save();
        // // create folder for organization
        // $path = public_path('storage/'.$org->name);
        // if(!file_exists($path)){
        //     mkdir($path, 0777, true);
        // }

        // $user = new User();
        // $user->name = $org->name;
        // $user->email = $org->email;
        // $user->password = Hash::make(123456);
        // $user->org_id = $org->id;
        // $colors = ['orange','purple','green','pink','yellow','blue'];
        // $user->color = $colors[array_rand($colors)];
        // $user->saveQuietly();

        // Team::factory()
        //     ->count(4)
        //     ->create();

        // Client::withoutEvents(function () {
        //     Client::factory()
        //         ->count(5)
        //         ->create();
        // });


        // Project::withoutEvents(function () {
        //     Project::factory()
        //         ->count(10)
        //         ->create();
        // });



        // User::factory()
        //     ->count(9)
        //     ->create();

        $this->call(PermissionSedder::class);

        // assign admin role to the user
        // setPermissionsTeamId(1);
        // $user->assignRole(1);




    }
}

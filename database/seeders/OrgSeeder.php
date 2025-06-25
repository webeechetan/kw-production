<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Organization;
use App\Models\User;
use App\Models\Project;
use App\Models\Team;
use App\Models\Client;
use App\Models\Task;
use App\Models\Comment;
use Illuminate\Support\Facades\Hash;
use App\Helpers\Helper;



class OrgSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $org = new Organization();
        $org->name = 'Webeesocial';
        $org->email = 'wbs@gmail.com';
        $org->password = Hash::make('123456');
        $org->image = Helper::createAvatar($org->name,'users');
        $org->save();
        // create folder for organization
        $path = public_path('storage/'.$org->name);
        if(!file_exists($path)){
            mkdir($path, 0777, true);
        }

        // user for organization

        $user = new User();
        $user->name = $org->name;
        $user->email = $org->email;
        $user->password = Hash::make($org->password);
        $user->org_id = $org->id;
        $user->image = Helper::createAvatar($org->name,'users');
        $user->save();

        // clients

        $client1 = new Client();
        $client1->org_id = $org->id;
        $client1->name = 'Acma';
        $client1->description = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.';
        $client1->image = Helper::createAvatar($client1->name,'clients');
        $client1->save();
        // create folder for client
        $path = public_path('storage/'.$org->name.'/'.$client1->name);
        if(!file_exists($path)){
            mkdir($path, 0777, true);
        }

        $client = new Client();
        $client->org_id = $org->id;
        $client->name = 'Global Rails Group';
        $client->description = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';
        $client->image = Helper::createAvatar($client->name,'clients');
        $client->save();
        // create folder for client
        $path = public_path('storage/'.$org->name.'/'.$client->name);
        if(!file_exists($path)){
            mkdir($path, 0777, true);
        }

        // projects 

        $project = new Project();
        $project->org_id = $org->id;
        $project->client_id = 1;
        $project->name = 'Acma Website';
        $project->description = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';
        $project->image = Helper::createAvatar($project->name,'projects');
        $project->save();
        // create folder for project
        $path = public_path('storage/'.$org->name.'/'.$client1->name.'/'.$project->name);
        if(!file_exists($path)){
            mkdir($path, 0777, true);
        }

        $project = new Project();
        $project->org_id = $org->id;
        $project->client_id = 1;
        $project->name = 'Acma Mobile App';
        $project->image = Helper::createAvatar($project->name,'projects');
        $project->description = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';
        $project->save();
        // create folder for project
        $path = public_path('storage/'.$org->name.'/'.$client1->name.'/'.$project->name);
        if(!file_exists($path)){
            mkdir($path, 0777, true);
        }

        $project = new Project();
        $project->org_id = $org->id;
        $project->client_id = 2;
        $project->name = 'GRG Website';
        $project->image = Helper::createAvatar($project->name,'projects');
        $project->description = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';
        $project->save();
        // create folder for project
        $path = public_path('storage/'.$org->name.'/'.$client->name.'/'.$project->name);
        if(!file_exists($path)){
            mkdir($path, 0777, true);
        }

        $project = new Project();
        $project->org_id = $org->id;
        $project->client_id = 2;
        $project->name = 'GRG CA website';
        $project->image = Helper::createAvatar($project->name,'projects');
        $project->description = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';
        $project->save();
        // create folder for project
        $path = public_path('storage/'.$org->name.'/'.$client->name.'/'.$project->name);
        if(!file_exists($path)){
            mkdir($path, 0777, true);
        }

        // teams

        $team = new Team();
        $team->org_id = $org->id;
        $team->name = 'Tech Team';
        $team->description = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';
        $team->image = Helper::createAvatar($team->name,'teams');
        $team->save();

        $team = new Team();
        $team->org_id = $org->id;
        $team->name = 'Media Team';
        $team->description = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';
        $team->image = Helper::createAvatar($team->name,'teams');
        $team->save();

        // users

        $user = new User();
        $user->org_id = $org->id;
        $user->name = 'Ajay Kumar';
        $user->email = 'ajay@gmail.com';
        $user->password = Hash::make('12345678');
        $user->image = Helper::createAvatar($user->name,'users');
        try{
            $user->save();
            $user->teams()->attach(1);
        }catch(\Exception $e){
            dd($e);
        }

        $user = new User();
        $user->org_id = $org->id;
        $user->name = 'Roshan Jajoria';
        $user->email = 'roshan@gmail.com';
        $user->password = Hash::make('12345678');
        $user->image = Helper::createAvatar($user->name,'users');
        try{
            $user->save();
            $user->teams()->attach(1);
        }catch(\Exception $e){
            dd($e);
        }

        $user = new User();
        $user->org_id = $org->id;
        $user->name = 'Chetan Singh';
        $user->email = 'chetan@gmail.com';
        $user->password = Hash::make('12345678');
        $user->image = Helper::createAvatar($user->name,'users');
        try{
            $user->save();
            $user->teams()->attach(1);
        }catch(\Exception $e){
            dd($e);
        }

        $user = new User();
        $user->org_id = $org->id;
        $user->name = 'Shubham Dhayani';
        $user->email = 'shubham@gmail.com';
        $user->password = Hash::make('12345678');
        $user->image = Helper::createAvatar($user->name,'users');
        try{
            $user->save();
            $user->teams()->attach(2);
        }catch(\Exception $e){
            dd($e);
        }

        $user = new User();
        $user->org_id = $org->id;
        $user->name = 'Rahul Sharma';
        $user->email = 'rahul@gmail.com';
        $user->password = Hash::make('12345678');
        $user->image = Helper::createAvatar($user->name,'users');
        try{
            $user->save();
            $user->teams()->attach(2);
        }catch(\Exception $e){
            dd($e);
        }

        $user = new User();
        $user->org_id = $org->id;
        $user->name = 'Rajesh Kumar';
        $user->email = 'rajesh@gmail.com';
        $user->password = Hash::make('12345678');
        $user->image = Helper::createAvatar($user->name,'users');
        try{
            $user->save();
            $user->teams()->attach(2);
        }catch(\Exception $e){
            dd($e);
        }

        // tasks

        $task = new Task();
        $task->org_id = $org->id;
        $task->project_id = 1;
        $task->name = 'Acma Website Backup';
        $task->description = 'Hello @Ajay_Kumar @Roshan_Jajoria @Chetan_Singh Please take backup of acma website.';
        $task->status = 'pending';
        $task->assigned_by = 1;
        $task->created_by = 'web';
        $task->task_order = 1;
        $task->due_date = date('Y-m-d',strtotime('+1 day'));
        $task->mentioned_users = '1,2,3';
        try{
            $task->save();
            $task->users()->attach([1,2,3]);
            $comment = new Comment();
            $comment->task_id = $task->id;
            $comment->user_id = 3;
            $comment->created_by = 'web';
            $comment->comment = 'Hello team, please take backup of acma website. @Ajay_Kumar @Roshan_Jajoria @Chetan_Singh';
            $task->mentioned_users = '1,2,3';
            $comment->save();

        }catch(\Exception $e){
            dd($e);
        }

    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSedder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groups = [
            'Client Management' => [
                'Create Client',
                'Edit Client',
                'Delete Client',
                'View Client',
            ],
            'Project Management' => [
                'Create Project',
                'Edit Project',
                'Delete Project',
                'View Project',
            ],
            'User Management' => [
                'Create User',
                'Edit User',
                'Delete User',
                'View User',
            ],
            'Team Management' => [
                'Create Team',
                'Edit Team',
                'Delete Team',
                'View Team',
            ],
            'Task Management' => [
                'Create Task',
                'Edit Task',
                'Delete Task',
                'View Task',
            ],
            'Role Management' => [
                'Create Role',
                'Edit Role',
                'Delete Role',
                'View Role',
            ],
        ];

        foreach ($groups as $group => $permissions) {
            foreach ($permissions as $permission) {
                Permission::create(['name' => $permission, 'group_name' => $group]);
            }
        }

        // // create four default roles and assign permissions to them (Admin,HR,Manager,Employee)

        // $roles = [
        //     'Admin' => [
        //         'Create Client',
        //         'Edit Client',
        //         'Delete Client',
        //         'View Client',
        //         'Create Project',
        //         'Edit Project',
        //         'Delete Project',
        //         'View Project',
        //         'Create User',
        //         'Edit User',
        //         'Delete User',
        //         'View User',
        //         'Create Team',
        //         'Edit Team',
        //         'Delete Team',
        //         'View Team',
        //         'Create Task',
        //         'Edit Task',
        //         'Delete Task',
        //         'View Task',
        //         'Create Role',
        //         'Edit Role',
        //         'Delete Role',
        //         'View Role',
        //     ],
        //     'HR' => [
        //         'Create User',
        //         'Edit User',
        //         'Delete User',
        //         'View User',
        //     ],
        //     'Manager' => [
        //         'Create Project',
        //         'Edit Project',
        //         'Delete Project',
        //         'View Project',
        //         'Create Team',
        //         'Edit Team',
        //         'Delete Team',
        //         'View Team',
        //         'Create Task',
        //         'Edit Task',
        //         'Delete Task',
        //         'View Task',
        //     ],
        //     'Employee' => [
        //         'View Project',
        //         'View Team',
        //         'View Task',
        //     ],
        // ];


        // foreach ($roles as $role => $permissions) {
        //     $role = Role::create(['name' => $role, 'org_id' => 1]);
        //     $role->syncPermissions(Permission::whereIn('name', $permissions)->get());
        // }


    }
}

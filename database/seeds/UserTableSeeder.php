<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        $role_superadmin = Role::where('name', 'superadmin')->first();
        $role_admin = Role::where('name', 'admin')->first();
        
        $user = new User();
        $user->name = 'Aryel';
        $user->lastname = 'Dvorquez';
        $user->username = 'aryeldvorquez';
        $user->email = 'admin1@example.com';
        $user->password = bcrypt('12345');
        $user->status = 1;
        $user->creado_por = 1;
        $user->roles_id = $role_superadmin->id;
        $user->save();

        $user = new User();
        $user->name = 'Stephanie';
        $user->lastname = 'Saman';
        $user->username = 'ssaman';
        $user->email = 'admin2@example.com';
        $user->password = bcrypt('12345');
        $user->status = 1;
        $user->creado_por = 1;
        $user->roles_id = $role_admin->id;
        $user->save();
    }
}


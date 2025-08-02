<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $super = Role::create(['name' => 'super-admin']);
        $admin = Role::create(['name' => 'admin']);
        $admin = Role::create(['name' => 'visitor-registration']);
        $conference = Role::create(['name' => 'conference-registration']);
        $badges = Role::create(['name' => 'badges-office']);
        

        //create user
        $tawanda = User::create([
            'name' => 'Tawanda',
            'email' => 'digital@zitf.co.zw',
            'password' => Hash::make('z1tf3v3nt5@123')
        ]);

        //assign role
        $tawanda->assignRole('super-admin');
    }
}

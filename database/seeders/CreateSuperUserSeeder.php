<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;

class CreateSuperUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superUser=User::create([
            'email'=>'admin@gmail.com',
            'name'=>'Admin',
            'password'=>Hash::make('12345678'),
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);

        Role::create([
            'name'=>'super-user',
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);

        $superUser->assignRole('super-user');
    }
}

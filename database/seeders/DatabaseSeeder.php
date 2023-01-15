<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
<<<<<<< Updated upstream
        //$users = User::factory()->count(10)->create();
        $roles = Role::factory()->count(4)->create();
        /*User::factory(70)->each(function($user) use ($roles) {
            $user->role = $roles->random()->role;
        } )->create();*/

        User::factory(20)->create()
            ->each(function($user) use ($roles) {
            $user->role_id = $roles->random()->id;
            $user->save(); 
        });

=======
        User::factory()->count(10)->create();
        // Role::factory()->count(4)->create();
>>>>>>> Stashed changes
    }
}
<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Str;

class UserSeed extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $user = User::create([
            'name' => Str::random(10),
            'email' => Str::random(10) . '@gmail.com',
            'district' => ('1'),
            'department' => ('1'),
            'password' => bcrypt('secret'),
            'responsible_specialist' => Str::random(10),
            'city_phone' => Str::random(10),
            'mobile_phone' => Str::random(10),
            'director' => Str::random(10),
            'directors_phone' => Str::random(10),
        ]);
        $user->assignRole('user');
    }
}

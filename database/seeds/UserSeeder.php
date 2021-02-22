<?php


    class UserSeeder extends \Illuminate\Database\Seeder
    {

        public function run(){

            DB::table('users')->insert([
                'name' => 'System Admin',
                'email' => 'admin@dev.com',
                'password' => bcrypt('admin123'),
                'status' => STATUS_PUBLIC,
                'api_token' => Str::random(60),

            ]);
            $user = \App\User::where('email','admin@dev.com')->first();
            $user->assignRole('administrator');

        }
    }
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(1000)->create();

        // $user=new User();
        // $user->name="ekram";
        // $user->email="ekram@gmail.com";
        // $user->password=Hash::make("password");
        // $user->save();

        // for($i=0;$i<1000;$i++) {
        //     $user=new User();
        //     $user->name="user ".$i;
        //     $user->email="user ".$i."@gmail.com";
        //     $user->password=Hash::make("password");
        //     $user->save();
        // }


    }
}

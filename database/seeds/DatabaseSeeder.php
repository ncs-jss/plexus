<?php

use Illuminate\Database\Seeder;
use App\Society;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $society = new Society;
        $society->username = 'nibble';
        $society->email = 'nibble@gmail.com';
        $society->password = Hash::make('helloworld');
        $society->privilege = 1;
        $society->socName = 'Nibble Computer Society';

        $society->save();
        echo "Seeding of Society is completed";
    }
}

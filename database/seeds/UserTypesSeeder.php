<?php

use Illuminate\Database\Seeder;

class UserTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userTypes = [];
        $userTypes[] = ['name' => 'Standard'];
        $userTypes[] = ['name' => 'Founding Member'];
        $userTypes[] = ['name' => 'Admin'];
        $userTypes[] = ['name' => 'Developer'];
        $userTypes[] = ['name' => 'Police'];
        $userTypes[] = ['name' => 'Copywriter'];
        $userTypes[] = ['name' => 'Can Read Everything'];
        $userTypes[] = ['name' => 'Advertiser'];
        $userTypes[] = ['name' => 'Charity'];

        $count = 0;

        foreach ($userTypes as $userType) {
            \App\UserType::insert([
                'name' => $userType['name'],
                'sort_order' => $count,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $count++;
        }
    }
}

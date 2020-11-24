<?php

use Illuminate\Database\Seeder;

class UserStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userStatuses = [];
        $userStatuses[] = ['status' => 'Active'];
        $userStatuses[] = ['status' => 'Deleted'];
        $userStatuses[] = ['status' => 'Reported'];
        $userStatuses[] = ['status' => 'In Review'];
        $userStatuses[] = ['status' => 'Banned'];

        foreach ($userStatuses as $userStatus) {
            \App\UserStatus::insert([
                'status' => $userStatus['status'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}

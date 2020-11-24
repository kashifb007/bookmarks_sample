<?php

use Illuminate\Database\Seeder;

class LinkStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $linkStatuses = [];
        $linkStatuses[] = ['status' => 'Live'];
        $linkStatuses[] = ['status' => 'Deleted'];
        $linkStatuses[] = ['status' => 'Reported'];
        $linkStatuses[] = ['status' => 'In Review'];
        $linkStatuses[] = ['status' => 'Banned'];

        $count = 0;

        foreach ($linkStatuses as $linkStatus) {
            \App\LinkStatus::insert([
                'status' => $linkStatus['status'],
                'sort_order' => $count,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $count++;
        }
    }

}

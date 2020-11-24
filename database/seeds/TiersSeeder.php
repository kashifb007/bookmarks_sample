<?php

use Illuminate\Database\Seeder;

class TiersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tiers = [];

        $tiers[] = [
            'name' => 'Lite - 30 bookmarks',
            'description' => 'Starter subscription.',
            'monthly_amount' => 0,
            'annual_amount' => 0,
            'lifetime_amount' => 0,
            'limit' => 30,
            'visibility' => 'public',
            'colour' => 'blue'
            ];

        $tiers[] = [
            'name' => 'medium - 100 bookmarks',
            'description' => '100 bookmarks',
            'monthly_amount' => 3,
            'annual_amount' => 20,
            'lifetime_amount' => 300,
            'limit' => 100,
            'visibility' => 'public',
            'colour' => 'blue'
        ];

        $tiers[] = [
            'name' => 'some fuck 300 bookmarks',
            'description' => '300 bookmarks',
            'monthly_amount' => 5,
            'annual_amount' => 50,
            'lifetime_amount' => 100,
            'limit' => 300,
            'visibility' => 'public',
            'colour' => 'blue'
        ];

        $tiers[] = [
            'name' => 'Unlimited',
            'description' => 'God Like',
            'monthly_amount' => 10,
            'annual_amount' => 100,
            'lifetime_amount' => 300,
            'limit' => null,
            'visibility' => 'public',
            'colour' => 'green'
        ];

        $tiers[] = [
            'name' => 'Founding Member',
            'description' => 'Thanks to the founders',
            'monthly_amount' => 0,
            'annual_amount' => 0,
            'lifetime_amount' => 0,
            'limit' => null,
            'visibility' => 'hidden',
            'colour' => 'gold'
        ];

        $count = 0;

        foreach ($tiers as $tier) {
            \App\Tiers::insert([
                'name' => $tier['name'],
                'description' => $tier['description'],
                'monthly_amount' => $tier['monthly_amount'],
                'annual_amount' => $tier['annual_amount'],
                'lifetime_amount' => $tier['lifetime_amount'],
                'limit' => $tier['limit'],
                'visibility' => $tier['visibility'],
                'colour' => $tier['colour'],
                'sort_order' => $count,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $count++;
        }
    }
}

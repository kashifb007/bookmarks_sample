<?php

use Illuminate\Database\Seeder;

class SitesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sites = [];
        $sites[] = [
            'base_uri' => 'pornhub.com',
            'title' => 'pornhub',
            'description' => 'fake news',
            'last_ping' => now(),
            'last_logo_update' => now(),
            'status_code' => 200,
            'status_id' => 5
        ];

        foreach ($sites as $site) {
            \App\Site::insert([
                'base_uri' => $site['base_uri'],
                'title' => $site['title'],
                'description' => $site['description'],
                'last_ping' => $site['last_ping'],
                'last_logo_update' => $site['last_logo_update'],
                'status_code' => $site['status_code'],
                'status_id' => $site['status_id'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

}

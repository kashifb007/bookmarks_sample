<?php

use Illuminate\Database\Seeder;

class ReportTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $reportTypes = [];
        $reportTypes[] = ['name' => 'Criminal Activity'];
        $reportTypes[] = ['name' => 'Porn'];
        $reportTypes[] = ['name' => 'Drugs'];
        $reportTypes[] = ['name' => 'Child Abuse'];
        $reportTypes[] = ['name' => 'False Information'];
        $reportTypes[] = ['name' => 'Religious Hate'];
        $reportTypes[] = ['name' => 'Racism'];
        $reportTypes[] = ['name' => 'Hate Crime'];
        $reportTypes[] = ['name' => 'Weight Loss Medicine'];
        $reportTypes[] = ['name' => 'Sex Toys'];
        $reportTypes[] = ['name' => 'Suspected Bot Account'];
        $reportTypes[] = ['name' => 'Suspected Abuse Account'];

        $count = 0;

        foreach ($reportTypes as $reportType) {
            \App\ReportType::insert([
                'name' => $reportType['name'],
                'sort_order' => $count,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $count++;
        }
    }
}

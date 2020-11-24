<?php

use Illuminate\Database\Seeder;

class PaymentTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $paymentTypes = [];
        $paymentTypes[] = ['name' => 'Subscription Payment'];
        $paymentTypes[] = ['name' => 'Donation'];
        $paymentTypes[] = ['name' => 'One Off'];
        $paymentTypes[] = ['name' => 'Refund'];
        $paymentTypes[] = ['name' => 'Credit'];

        $count = 0;

        foreach ($paymentTypes as $paymentType) {
            \App\PaymentTypes::insert([
                'name' => $paymentType['name'],
                'sort_order' => $count,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $count++;
        }
    }

}

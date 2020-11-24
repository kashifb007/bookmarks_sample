<?php

use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        for($x=0; $x<10; $x++) {
//            \App\Coupon::insert([
//                'coupon_code' => uniqid('',true),
//                'tier_id' => 4,
//                'created_at' => now(),
//                'updated_at' => now()
//            ]);
//        }
//
//        for($x=0; $x<10; $x++) {
//            \App\Coupon::insert([
//                'coupon_code' => uniqid('',true),
//                'tier_id' => 5,
//                'created_at' => now(),
//                'updated_at' => now()
//            ]);
//        }

        $coupons = [];
        $coupons[] = [
            'coupon_code' => 'Rickinthewall',
            'tier_id' => 4
        ];
        $coupons[] = [
            'coupon_code' => 'awilliamscomedy',
            'tier_id' => 4
        ];
        $coupons[] = [
            'coupon_code' => 'kbeanie93',
            'tier_id' => 4
        ];
        $coupons[] = [
            'coupon_code' => 'garethicke',
            'tier_id' => 4
        ];
        $coupons[] = [
            'coupon_code' => 'davidicke',
            'tier_id' => 4
        ];
        $coupons[] = [
            'coupon_code' => 'zerosum24',
            'tier_id' => 4
        ];
        $coupons[] = [
            'coupon_code' => 'o_rips',
            'tier_id' => 4
        ];
        $coupons[] = [
            'coupon_code' => 'DPotcner',
            'tier_id' => 4
        ];
        $coupons[] = [
            'coupon_code' => 'FatEmperor',
            'tier_id' => 4
        ];
        $coupons[] = [
            'coupon_code' => 'jadenozzz',
            'tier_id' => 4
        ];

        foreach ($coupons as $coupon) {
            \App\Coupon::insert([
                'coupon_code' => $coupon['coupon_code'],
                'tier_id' => 5,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}

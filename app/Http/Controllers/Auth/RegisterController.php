<?php

namespace App\Http\Controllers\Auth;

use App\Category;
use App\Coupon;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Subscription;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

/**
 * Class RegisterController
 * @package App\Http\Controllers\Auth
 * @author Kashif <kash@dreamsites.co.uk>
 * @created 23/09/2020
 */
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $messages = [
            'firstname.required' => 'First name is required.',
            'surname.required' => 'Surname is required.',
            'email.required' => 'Email is required.',
            'email.unique' => 'This email address is already registered.',
            'password.required' => 'Password is required.',
            'username.required' => 'Username is required.',
            'username.unique' => 'This username is already registered.'
        ];

        // whilst we're taking twitter accounts as coupon codes
        //$coupon = Coupon::where('coupon_code', ))->where('date_used', null)->take(1)->get();

//        $coupon = DB::table('coupons')
//            ->whereRaw('coupon_code = ?', [$data['coupon_code']])
//            ->whereRaw('date_used IS NULL')->get();
//
//        if ($coupon) {
//            foreach ($coupon as $cou) {
//                $data['coupon_id'] = $cou->id;
//                $data['coupon_code'] = $cou->coupon_code;
//            }
//        }
//
        $validation = [
            'firstname' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'min:8', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'username' => ['required', 'string', 'min:3', 'max:255', 'unique:users']
        ];

        if (isset($data['twitter_username'])) {
            $validation['twitter_username'] = ['required', 'string', 'max:255', 'unique:users'];
        }

        return Validator::make($data, $validation, $messages);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\User
     */
    protected
    function create(array $data)
    {
        try {

            DB::beginTransaction();
//            $coupon = DB::table('coupons')
//                ->whereRaw('coupon_code = ?', [strtolower($data['coupon_code'])])
//                ->whereRaw('date_used IS NULL')->get();
//
//            if ($coupon) {
//                foreach ($coupon as $cou) {
//                    $coupon_id = $cou->id;
//                }
//            }

            $user = User::create([
                'firstname' => $data['firstname'],
                'surname' => $data['surname'],
                'email' => $data['email'],
                'username' => $data['username'],
                'password' => Hash::make($data['password']),
                'twitter_username' => $data['twitter_username'] ?? null,
            ]);

//            $coupon = Coupon::findOrFail($coupon_id);
//
//            $coupon->user_id = $user->id;
//            $coupon->date_used = now();
//            $coupon->save();
//
//            $subscription = Subscription::create(
//                [
//                    'email' => $data['email'],
//                    'user_id' => $user->id,
//                    'tier_id' => $coupon->tier_id
//                ]
//            );

            $home = Category::create(
                [
                    'user_id' => $user->id,
                    'title' => 'Home',
                    'category_status' => 'base'
                ]
            );

            $favourites = Category::create(
                [
                    'user_id' => $user->id,
                    'title' => 'Favourites',
                    'category_status' => 'base'
                ]
            );

            DB::commit();

        } catch (\Exception $e) {
            //log error and return
            DB::rollBack();
            return [$e->getMessage()];
        }

        return $user;
    }
}

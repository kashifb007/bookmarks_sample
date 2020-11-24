<?php
/**
 * Class UserService
 * @package App\Services
 * @author Kashif <kash@dreamsites.co.uk>
 * @created 23/09/2020
 */

namespace App\Services;

use Validator;
use Illuminate\Http\Request;

class UserService
{

    public function login(Request $request)
    {
        $messages = [
            'password.required' => 'Password is required.',
            'username.required' => 'Email or Username is required.'
        ];

        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:3|max:255',
            'username' => 'required|string|min:3|max:255'
        ], $messages);

        if ($validator->fails()) {
            return redirect('login')
                ->withErrors($validator)
                ->withInput();
        }

        $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        if (auth()->attempt(array($fieldType => $request->username, 'password' => $request->password), $request->remember)) {
            return true;
        } else {
            return false;
        }

    }

}

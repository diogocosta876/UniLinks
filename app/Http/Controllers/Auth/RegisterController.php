<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

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
    protected $redirectTo = '/endregistration';

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
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'accounttag' => 'required|unique:App\Models\User,account_tag|string|min:1|max:254|regex:/^[a-zA-Z][a-zA-Z0-9-]*$/',
            'email' => 'required|string|email|max:255|unique:account|regex:/[a-zA-Z0-9]*@[a-zA-Z0-9]+(?>\.[a-zA-Z]+)+/',
            'password' => 'required|string|min:6|confirmed',
            'birthday' => 'required|string',
            'university' => 'required|string|max:255',
            'course' => 'required|string|max:255'
        ]);
    }

    public function messages() { // This is not working
        return [
            'accounttag.required' => 'An account tag is required',
            'accounttag.unique' => 'This account tag already exists',
            'accounttag.min' => 'Your account tag should be at least 1 character long',
            'accounttag.max' => 'Your account tag should be no longer than 255 characters',
            'accounttag.regex' => 'Your account tag should consist of numbers, letters and the character "-" only, starting with a letter',
            'email.required' => 'An e-mail is required',
            'email.unique' => 'An account with this e-mail already exists',
            'email.max' => 'Your e-mail should be no longer than 255 characters',
            'email.regex' => 'Your e-mail is in the wrong format',
            'password.required' => 'A password is required',
            'password.confirmed' => 'The passwords don\'t correspond',
            'password.min' => 'Your password should be at least 6 characters long',
            'birthday.required' => 'A birthday is required',
            'university.required' => 'An university is required',
            'course.required' => 'A course is required'
        ];
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'password' => bcrypt($data['password']),
            'account_tag' => $data['accounttag'],
            'name' => $data['accounttag'],
            'age' => date_diff(date_create(now()), date_create($data['birthday']))->format('%Y'),
            'birthday' => $data['birthday'],
            'is_private' => true,
            'email' => $data['email'],
            'university' => $data['university'],
            'course' => $data['course'],
            'is_verified' => false,
            'description' => "",
            'location' => "",
            'pronouns' => "",
            'is_admin' => false,
            'is_blocked' => false
        ]);
    }
}

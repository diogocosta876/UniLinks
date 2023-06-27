<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\RecoveryCode;

use App\Mail\PasswordRecovery;

use Mail;
use Validator;

class PasswordRecoveryController extends Controller
{
    public function recoveryShow() {
        return view('pages.recovery.show');
    }

    public function recoverySentShow($email) {
        return view('pages.recovery.sent', ['email' => $email]);
    }

    public function changePasswordShow() {        
        return view('pages.recovery.change');
    }

    public function recovery(Request $request) {
        $validator = Validator::make($request->all(), [ 
            'email' => 'required|string|email|max:255|regex:/[a-zA-Z0-9]*@[a-zA-Z0-9]+(?>\.[a-zA-Z]+)+/'
            ],
            [
            'email.required'=> 'An email is required',
            ]
        );
    
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $mailExists = User::where('account.email', '=', $request['email'])->exists();
        
        if (!$mailExists) return back()->with('emailnotfound', 'An account with this email does not exist');
        
        $user = User::where('account.email', '=', $request['email'])->first();

        $recoveryCode = "";

        do {

            $bytes = random_bytes(32);

            $recoveryCode = bin2hex($bytes);
            
        } while (RecoveryCode::where('code', '=', $recoveryCode)->exists()); // Generate another token if this one exists

        $newRecoveryCode = new RecoveryCode([
            'id_account' => $user->id_account,
            'code' => $recoveryCode,
            'valid_until' => date("Y-m-d H:i:s", strtotime('+2 hours'))
        ]);

        $newRecoveryCode->save();

        $mailData = [
            'account_tag' => $user->account_tag,
            'recovery_code' => $recoveryCode,
            'email' => $request['email'],
        ];
    
        Mail::to($mailData['email'])->send(new PasswordRecovery($mailData));

        return redirect(route('recovery.change'));
    }

    public function changePassword(Request $request) {
        $validator = Validator::make($request->all(), [ 
                'email' => 'required|string|email|max:255|regex:/[a-zA-Z0-9]*@[a-zA-Z0-9]+(?>\.[a-zA-Z]+)+/',
                'token' => 'required|string|max:64|regex:/[0-9a-f]{64}/',
                'password' => 'required|string|min:6|confirmed',
            ],
            [
                'email.required' => 'Please specify the email.',
                'token.required' => 'Please specify the token.'
            ]
        );

        if ($request['password'] != $request['password_confirmation']) {
            return redirect()->back()->with('response', 'The passwords do not match.');
        }

        $mail = $request['email'];
        $token = $request['token'];

        $user = User::where('email', '=', $mail)->first();

        if (!$user) return redirect()->back()->with('response', 'Wrong email.');

        $mailExists = RecoveryCode::where('code', '=', $token)
                                    ->where('id_account', '=', $user->id_account)
                                    ->exists();

        if (!$mailExists) return redirect()->back()->with('response', 'Wrong token.');
        
        $token = RecoveryCode::where('code', '=', $token)->where('id_account', '=', $user->id_account)->first();

        if (date($token->valid_until) < now()) {
            RecoveryCode::where('code', '=', $token)->where('id_account', '=', $user->id_account)->delete();

            return redirect()->back()->with('response', 'Token expired');
        }

        $mailExists = RecoveryCode::where('code', '=', $token)
                                    ->where('id_account', '=', $user->id_account)
                                    ->delete();
                                
        $change = User::where('email', '=', $mail)->update(["password" => Hash::make($request['password'])]);
        
        if (!$change) return redirect()->back()->with('response', 'Failed to change the password.');;

        return redirect(route('login'));
    }
}

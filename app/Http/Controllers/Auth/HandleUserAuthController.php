<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Jobs\SendOTPToUserJob;
use App\Http\Controllers\Controller;

class HandleUserAuthController extends Controller
{
    public function store(Request $request){
        try {
            $user = User::create([
                'phone_number' => $request->phone_number,
            ]);

            dispatch(new SendOTPToUserJob($user));

            return $this->success([
                'user' => $user,
                'access_token' => auth()->login($user),
                'token_type' => 'Bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ], 'User created successfully');
        } catch (\Exception $th) {
            return $this->error($th->getMessage(), $th->getCode());
        }
    }

    public function verify(Request $request){
        try {
            $user = User::where('phone_number', $request->phone_number)->first();

            if(!$user) return $this->error('User not found', 404);
            if($user->otp_verified->verified_at) return $this->error('User already verified', 400);
            if($user->otp_verified->otp != $request->otp) return $this->error('OTP not match', 400);

            $user->otp_verified->update([
                'verified_at' => now(),
            ]);

            return $this->success($user, 'User verified successfully');
        } catch (\Exception $th) {
            return $this->error($th->getMessage(), $th->getCode());
        }
    }
}

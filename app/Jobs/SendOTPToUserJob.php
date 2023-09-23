<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Services\SendOTPService;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendOTPToUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new job instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $otp = rand(100000, 999999);
        $this->user->otp_verified()->create([
            'otp' => $otp,
        ]);

        $message = "Kocek - JANGAN MEMBERITAHU KODE INI KE SIAPAPUN termasuk pihak Kocek. WASPADA TERHADAP KASUS PENIPUAN! KODE RAHASIA: {$otp}. @www.devinnovation.tech#{$otp}";

        if(substr($this->user->phone_number, 0, 2) != '62'){
            $phone_number = '62'.substr($this->user->phone_number, 1);
        }

        Http::post(env('WA_INSTANCE_HOST')."/message/text?key=".env('WA_INSTANCE_KEY'), [
            'id' => $phone_number,
            'message' => $message,
        ]);
    }
}

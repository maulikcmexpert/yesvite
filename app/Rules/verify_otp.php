<?php

namespace App\Rules;

use App\Models\Password_reset;
use Illuminate\Support\Facades\Auth;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class verify_otp implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $otp = request()->input('otp');
        $email = request()->input('email');


        // Perform your validation logic here
        $exists =  Password_reset::where('email', $email)
            ->where('token', $otp)
            ->where('expires_at', '>', now())
            ->exists();

        if (!$exists) {
            $fail("otp is invalid.");
        }
    }
}

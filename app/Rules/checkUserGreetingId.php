<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\EventGreeting;
use Illuminate\Support\Facades\Auth;

class checkUserGreetingId implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $user_id = Auth::guard('api')->user()->id;
        $greeting_card_id = request()->input('greeting_card_id');


        // Perform your validation logic here
        $exists = EventGreeting::where('user_id', $user_id)
            ->where('id', $greeting_card_id)
            ->where('user_id', $user_id)
            ->exists();

        if (!$exists) {
            $fail("The combination of user and greeting card id is invalid.");
        }
    }
}

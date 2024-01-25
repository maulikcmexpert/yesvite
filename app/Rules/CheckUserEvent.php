<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class CheckUserEvent implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        $user_id = Auth::guard('api')->user()->id;

        $event_id = request()->input('event_id');

        // Perform your validation logic here
        $exists = Event::where(['user_id' => $user_id, 'id' => $event_id])
            ->exists();

        if (!$exists) {
            $fail("The combination of user and event is invalid.");
        }
    }
}

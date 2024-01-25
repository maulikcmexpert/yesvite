<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\EventPost;
use Illuminate\Support\Facades\Auth;

class checkUserEventPost implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $user_id = Auth::guard('api')->user()->id;
        $event_post_id = request()->input('event_post_id');
        $event_id = request()->input('event_id');


        // Perform your validation logic here
        $exists = EventPost::where('user_id', $user_id)
            ->where('id', $event_post_id)
            ->where('event_id', $event_id)
            ->exists();

        if (!$exists) {
            $fail("The combination of user and post is invalid.");
        }
    }
}

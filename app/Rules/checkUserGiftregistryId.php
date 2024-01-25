<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\EventGiftRegistry;
use Illuminate\Support\Facades\Auth;

class checkUserGiftregistryId implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $user_id = Auth::guard('api')->user()->id;
        $gift_registry_id = request()->input('gift_registry_id');


        // Perform your validation logic here
        $exists = EventGiftRegistry::where(['user_id' => $user_id, 'id' => $gift_registry_id])

            ->exists();

        if (!$exists) {
            $fail("The combination of user and gift registry id is invalid.");
        }
    }
}

<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AtLeastOnePresentRule implements ValidationRule
{
    public function validate($attribute, $value, Closure $fail)
    {
        $tokens = request()->only([
            'facbook_token_id',
            'gmail_token_id',
            'apple_token_id',
            'instagram_token_id'
        ]);

        $presentTokens = array_filter($tokens, function ($token) {
            return !empty($token);
        });

        if (count($presentTokens) < 1) {
            $fail("$attribute should have at least one token present.");
        }
    }
}

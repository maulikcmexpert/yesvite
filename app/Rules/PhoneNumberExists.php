<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class PhoneNumberExists implements Rule
{
    protected $table;
    protected $column;

    public function __construct($table = 'users', $column = 'phone_number')
    {
        $this->table = $table;
        $this->column = $column;
    }

    public function passes($attribute, $value)
    {
        return !DB::table($this->table)->where($this->column, $value)->exists();
    }

    public function message()
    {
        return 'Phone Number is already exists';
    }
}

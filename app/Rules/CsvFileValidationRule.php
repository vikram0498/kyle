<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CsvFileValidationRule implements Rule
{
    public function passes($attribute, $value)
    {
        $fileExtension = strtolower(pathinfo($value->getClientOriginalName(), PATHINFO_EXTENSION));
        return $fileExtension === 'csv';
    }

    public function message()
    {
        return 'The :attribute must be a valid CSV file.';
    }
}

<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class AcademicEmail implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $allowedDomains = ['.edu', '.ac.in', '.edu.in', 'example.com'];
        $isValid = false;

        foreach ($allowedDomains as $domain) {
            if (str_ends_with(strtolower($value), $domain)) {
                $isValid = true;
                break;
            }
        }

        if (!$isValid) {
            $fail('The :attribute must be a valid academic or institute email address (e.g., .edu, .ac.in).');
        }
    }
}

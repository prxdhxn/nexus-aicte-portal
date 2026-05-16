<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Adoption;

class UniqueAdoption implements ValidationRule
{
    protected $curriculumId;

    public function __construct($curriculumId)
    {
        $this->curriculumId = $curriculumId;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $exists = Adoption::where('user_id', auth()->id())
            ->where('curriculum_id', $this->curriculumId)
            ->exists();

        if ($exists) {
            $fail('Your institute has already submitted an adoption plan for this curriculum. Only one submission is allowed.');
        }
    }
}

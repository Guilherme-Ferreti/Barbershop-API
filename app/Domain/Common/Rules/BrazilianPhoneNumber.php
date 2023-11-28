<?php

declare(strict_types=1);

namespace App\Domain\Common\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class BrazilianPhoneNumber implements ValidationRule
{
    /**
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! preg_match('^(5{2})(\d{2})(9?)([\d]{8}$)^', $value)) {
            $fail('validation.brazilian_phone_number')->translate();
        }
    }
}

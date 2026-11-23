<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class DictionaryAlphabet implements ValidationRule
{
    public function __construct(
        private string $language
    ) {}

    public function validate(
        string $attribute,
        mixed $value,
        Closure $fail
    ): void {
        if (! is_string($value)) {
            return;
        }

        $patterns = [
            'en' => "/^[a-zA-Z]+(?:[ '\x{2019}-][a-zA-Z]+)*$/u",
            'uk' => "/^[А-ЩЬЮЯЄІЇҐа-щьюяєіїґ]+(?:[ '\x{2019}-][А-ЩЬЮЯЄІЇҐа-щьюяєіїґ]+)*$/u",
        ];

        if (! isset($patterns[$this->language])) {
            $fail('Непідтримувана мова.');

            return;
        }

        if (! preg_match($patterns[$this->language], $value)) {
            $fail(
                $this->language === 'en'
                    ? 'Використовуйте англійські літери.'
                    : 'Використовуйте українські літери.'
            );
        }
    }
}

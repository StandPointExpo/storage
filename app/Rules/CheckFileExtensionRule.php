<?php

namespace App\Rules;

use App\Models\CrmFile;
use Illuminate\Contracts\Validation\Rule;

class CheckFileExtensionRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     * @throws \App\Exceptions\FileExtException
     */
    public function passes($attribute, $value): bool
    {
        $type = (new CrmFile)->getType($value);
        if (!is_null($type)) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The :file extension not support.';
    }
}

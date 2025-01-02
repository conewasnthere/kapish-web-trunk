<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ModelValidator implements Rule
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
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // should validate roblox xml files
        // still exploitable in theory, but who cares
        // best that can be done lol
        if(!request()->hasFile($attribute)) {
            return true;
        }

        $model = request()->file($attribute)->get();

        return str_starts_with($model, '<roblox ') && str_ends_with($model, '</roblox>');
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Not a valid Roblox XML file.';
    }
}

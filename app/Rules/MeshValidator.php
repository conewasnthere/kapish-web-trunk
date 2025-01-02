<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MeshValidator implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    // it will never support version 3
    protected $version_headers = [
        'version 1.00',
        'version 2.00'
    ];

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
        // should validate mesh files
        // still exploitable in theory, but who cares
        // best that can be done lol
        if(!request()->hasFile($attribute)) {
            return true;
        }

        // check for version in mesh file header
        // check the first 12 bytes not the entire file        
        return (in_array(substr(request()->file($attribute)->get(), 0, 12), $this->version_headers));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Not a valid mesh.';
    }
}

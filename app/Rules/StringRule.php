<?php

namespace App\Rules;

class StringRule
{
    public static function rules(string $name, bool $required): array
    {
        return [
            $name => [
                $required ? 'required' : 'nullable',
                'string',
                'max:50',
            ],
        ];
    }

    public static function messages(string $name, bool $required): array
    {
        $messages = [];

        if ($required) {
            $messages[$name.'.required'] = ucwords(str_replace('_', ' ', $name)).' is required.';
        }

        $messages[$name.'.max'] = ucwords(str_replace('_', ' ', $name)).' is too long.';
        $messages[$name.'.string'] = ucwords(str_replace('_', ' ', $name)).' must be a string.';

        return $messages;
    }
}

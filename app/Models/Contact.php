<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'reply',
        'is_replied',
    ];

    protected function casts(): array
    {
        return [
            'is_replied' => 'boolean',
        ];
    }
}

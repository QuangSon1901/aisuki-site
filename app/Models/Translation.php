<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    protected $fillable = [
        'group', 'key', 'value', 'language_code'
    ];

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_code', 'code');
    }
}
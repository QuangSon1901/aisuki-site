<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key', 'value', 'group', 'is_translatable'
    ];

    protected $casts = [
        'is_translatable' => 'boolean',
    ];

    public function translations()
    {
        return $this->hasMany(Translation::class, 'key', 'key')
            ->where('group', 'settings');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'notifiable_type',
        'notifiable_id',
        'title',
        'content',
        'is_read',
        'is_processed',
        'processed_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_processed' => 'boolean',
        'processed_at' => 'datetime',
    ];

    public function notifiable()
    {
        return $this->morphTo();
    }

    public function markAsRead()
    {
        return $this->update(['is_read' => true]);
    }

    public function markAsProcessed()
    {
        return $this->update([
            'is_processed' => true,
            'processed_at' => now(),
        ]);
    }

    // Get icon based on notification type
    public function getIconClass()
    {
        return match($this->type) {
            'order' => 'fa-shopping-cart',
            'reservation' => 'fa-calendar-alt',
            'contact' => 'fa-envelope',
            default => 'fa-bell'
        };
    }

    // Get color based on notification type
    public function getColorClass()
    {
        return match($this->type) {
            'order' => 'primary',
            'reservation' => 'success',
            'contact' => 'info',
            default => 'secondary'
        };
    }
}
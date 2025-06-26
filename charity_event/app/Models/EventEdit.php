<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EventEdit extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'event_name',
        'description',
        'location',
        'organizer_name',
        'phone',
        'goal',
        'status',
        'reason',
        'created_at',
    ];

    public $timestamps = false;

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

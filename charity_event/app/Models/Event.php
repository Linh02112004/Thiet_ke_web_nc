<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'user_id',
        'event_name',
        'description',
        'location',
        'goal',
        'organizer_name',
        'phone',
        'bank_account',
        'bank_name',
        'status',
    ];

    public function organization()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}

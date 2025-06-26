<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'donor_id',
        'amount',
        'donated_at',
    ];

    public $timestamps = false;

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function donor()
    {
        return $this->belongsTo(User::class, 'donor_id');
    }
}

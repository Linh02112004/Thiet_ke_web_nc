<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['event_name', 'start_date', 'end_date', 'location', 'amount_raised', 'amount_target', 'status', 'organization_id'];

    // Quan hệ với Organization (User)
    public function organization()
    {
        return $this->belongsTo(User::class, 'organization_id');
    }
}

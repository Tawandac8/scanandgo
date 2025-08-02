<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubEvent extends Model
{
    protected $fillable = [
        'event_id',
        'name',
        'start_date',
        'end_date',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function getFormattedStartDateAttribute()
    {
        return $this->start_date->format('Y-m-d');
    }

    public function getFormattedEndDateAttribute()
    {
        return $this->end_date ? $this->end_date->format('Y-m-d') : null;
    }
}

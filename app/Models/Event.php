<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'name',
        'year',
        'start_date',
        'end_date',
        'event_code'
    ];

    public function getFormattedStartDateAttribute()
    {
        return $this->start_date->format('Y-m-d');
    }

    public function getFormattedEndDateAttribute()
    {
        return $this->end_date ? $this->end_date->format('Y-m-d') : null;
    }

    public function badges(){
        return $this->hasMany(Badge::class);
    }

    public function getBadgeCount()
    {
        return $this->badges()->count();
    }

    public function sub_events()
    {
        return $this->hasMany(SubEvent::class);
    }
}

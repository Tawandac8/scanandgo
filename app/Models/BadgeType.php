<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BadgeType extends Model
{
    protected $fillable = [
        'name',
        'event_id',
        'sub_event_id',
        'is_active'
    ];

    public function badges()
    {
        return $this->hasMany(Badge::class);
    }

    public function getBadgeCount()
    {
        return $this->badges()->count();
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function subEvent()
    {
        return $this->belongsTo(SubEvent::class);
    }
}

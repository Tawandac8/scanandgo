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

    /**
     * Format the start date to Y-m-d format.
     *
     * @return string
     */
    public function getFormattedStartDateAttribute()
    {
        return $this->start_date->format('Y-m-d');
    }

    /**
     * Format the end date to Y-m-d format, or return null if there is no end date.
     *
     * @return string|null
     */
    public function getFormattedEndDateAttribute()
    {
        return $this->end_date ? $this->end_date->format('Y-m-d') : null;
    }

    /**
     * Get all badges associated with the event
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function badges(){
        return $this->hasMany(Badge::class);
    }

    /**
     * Get the count of all badges associated with the event
     *
     * @return int
     */
    public function getBadgeCount()
    {
        return $this->badges()->count();
    }

    /**
     * Get the sub events that belong to the Event
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sub_events()
    {
        return $this->hasMany(SubEvent::class);
    }

    /**
     * The users that belong to the Event
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users(){
        return $this->hasMany(User::class);
    }
}

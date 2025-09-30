<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    protected $fillable = [
        'event_id',
        'sub_event_id', 
        'reg_code',
        'title',
        'first_name',
        'last_name',
        'name',
        'company_name',
        'position',
        'email',
        'phone',
        'mobile',
        'badge_type_id',
        'profile', //profile picture
        'background', //badge design background
        'city_id',
        'country_id',
        'is_online_registration',
        'is_printed',
        'printed_copies'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function sub_event()
    {
        return $this->belongsTo(SubEvent::class);
    }

    public function badge_type(){
        return $this->belongsTo(BadgeType::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}

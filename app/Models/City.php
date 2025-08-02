<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = [
        'name',
        'country_id',
    ];

    public function badges()
    {
        return $this->hasMany(Badge::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}

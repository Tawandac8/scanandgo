<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Exhibitor extends Model
{
    protected $fillable = [
        'company_name',
        'code',
        'event_code'
    ];

    public function badges(){
        return $this->hasMany(Badge::class);
    }
}

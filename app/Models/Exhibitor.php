<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Exhibitor extends Model
{
    protected $fillable = [
        'company_name',
        'code',
        'event_code',
    ];

    /**
     * Get the badges associated with the exhibitor.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function badges(){
        return $this->hasMany(Badge::class);
    }
}

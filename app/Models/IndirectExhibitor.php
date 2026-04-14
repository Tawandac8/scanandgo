<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndirectExhibitor extends Model
{
    protected $fillable = [
        'company_name',
        'code',
        'exhibitor_id',
    ];

    public function exhibitor(){
        return $this->belongsTo(Exhibitor::class);
    }

    public function indirect_exhibitor_badges(){
        return $this->hasMany(IndirectExhibitorBadge::class);
    }
}

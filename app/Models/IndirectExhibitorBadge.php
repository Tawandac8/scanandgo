<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndirectExhibitorBadge extends Model
{
    protected $fillable = [
        'name',
        'badge_type_id',
        'indirect_exhibitors_id',
        'batch_number',
        'is_printed',
        'printed_count',
        'printed_at',
        'printed_by',
        'serial_number',
        'printed_in_bulawayo'
    ];

    public function badge_type(){
        return $this->belongsTo(BadgeType::class);
    }

    public function indirect_exhibitor(){
        return $this->belongsTo(IndirectExhibitor::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExhibitorBadge extends Model
{
    protected $fillable = [
        'exhibitor_id',
        'name',
        'badge_type_id',
        'is_printed',
        'printed_copies',
        'batch_number'
    ];


    public function exhibitor()
    {
        return $this->belongsTo(Exhibitor::class);
    }
    /**
     * Get the badge type that this exhibitor badge belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function badge_type(){
        return $this->belongsTo(BadgeType::class);
    }
}

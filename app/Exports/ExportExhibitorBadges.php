<?php

namespace App\Exports;

use App\Models\ExhibitorBadge;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportExhibitorBadges implements FromCollection
{
    public $event;

    public function __construct($event) {
        $this->event = $event;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return ExhibitorBadge::where('event_id', $this->event->id)->where('is_printed', 1)->get();
    }
}

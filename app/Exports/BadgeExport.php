<?php

namespace App\Exports;

use App\Models\Badge;
use Maatwebsite\Excel\Concerns\FromCollection;

class BadgeExport implements FromCollection
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
        return Badge::where('sub_event_id', $this->event->id)->where('is_printed', 1)->get();
    }
}

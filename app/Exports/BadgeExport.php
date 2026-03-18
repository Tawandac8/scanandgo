<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BadgeExport implements FromView
{
    public $badges;

    public function __construct($badges)
    {
        $this->badges = $badges;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        return view('delegates.exports', [
            'badges' => $this->badges
        ]);
    }
}


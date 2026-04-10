<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class ExportExhibitorBadges implements FromCollection, WithHeadings, WithMapping
{
    public $badges;

    public function __construct($badges) {
        $this->badges = $badges;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->badges;
    }

    public function headings(): array
    {
        return [
            'Name',
            'Company Name',
            'Badge Type',
            'Serial Number',
            'Printed By',
            'Printed Date',
        ];
    }

    public function map($badge): array
    {
        return [
            $badge->name,
            $badge->exhibitor->company_name ?? '',
            $badge->badge_type->name ?? '',
            $badge->serial_number,
            $badge->printed_by,
            $badge->printed_date ? Carbon::parse($badge->printed_date)->format('d M Y') : '',
        ];
    }
}

<?php

namespace App\Exports;

use App\Models\Badge;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class BadgesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $badges;

    public function __construct($badges)
    {
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
            'Registration Code',
            'Serial Number',
            'Printed Copies',
            'Printed Date',
            'Printed By',
        ];
    }

    public function map($badge): array
    {
        return [
            $badge->name,
            $badge->company_name,
            $badge->badge_type->name ?? '',
            $badge->reg_code,
            $badge->serial_number,
            $badge->printed_copies,
            Carbon::parse($badge->printed_date)->format('d M'),
            $badge->printed_by,
        ];
    }
}

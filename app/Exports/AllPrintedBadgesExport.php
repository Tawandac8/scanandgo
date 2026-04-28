<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromGenerator;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;
use App\Models\Badge;
use App\Models\ExhibitorBadge;
use App\Models\IndirectExhibitorBadge;

class AllPrintedBadgesExport implements FromGenerator, WithHeadings
{
    protected $event;

    public function __construct($event)
    {
        $this->event = $event;
    }

    public function generator(): \Generator
    {
        $badges = Badge::where('event_id', $this->event->id)
            ->where('is_printed', 1)
            ->whereHas('badge_type', function($q) {
                $q->where('name', '!=', 'Visitor');
            })
            ->with('badge_type')
            ->cursor();

        foreach ($badges as $item) {
            yield [
                $item->name,
                $item->company_name,
                $item->badge_type->name ?? '',
                $item->reg_code,
                $item->serial_number,
                $item->printed_copies,
                $item->printed_date ? Carbon::parse($item->printed_date)->format('d M Y') : '',
                $item->printed_by,
            ];
        }

        $exhibitor_badges = ExhibitorBadge::whereHas('exhibitor', function($q) {
            $q->where('event_code', $this->event->event_code);
        })->where('is_printed', 1)->with(['exhibitor', 'badge_type'])->cursor();

        foreach ($exhibitor_badges as $item) {
            yield [
                $item->name,
                $item->exhibitor->company_name ?? '',
                $item->badge_type->name ?? '',
                '',
                $item->serial_number,
                $item->printed_copies,
                $item->printed_date ? Carbon::parse($item->printed_date)->format('d M Y') : '',
                $item->printed_by,
            ];
        }

        $indirect_exhibitor_badges = IndirectExhibitorBadge::whereHas('indirect_exhibitor.exhibitor', function($q) {
            $q->where('event_code', $this->event->event_code);
        })->where('is_printed', 1)->with(['indirect_exhibitor', 'badge_type'])->cursor();

        foreach ($indirect_exhibitor_badges as $item) {
            yield [
                $item->name,
                $item->indirect_exhibitor->company_name ?? '',
                $item->badge_type->name ?? '',
                '',
                $item->serial_number,
                $item->printed_count,
                $item->printed_at ? Carbon::parse($item->printed_at)->format('d M Y') : '',
                $item->printed_by,
            ];
        }
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
}

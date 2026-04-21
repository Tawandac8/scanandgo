<?php

namespace App\Http\Controllers;

use App\Models\ExhibitorBadge;
use App\Models\Badge;
use App\Models\Event;
use App\Models\SubEvent;
use App\Models\BadgeType;

class APIController extends Controller
{
    public function exhibitorBadges(){
        //get badges printed this year
        $badges = ExhibitorBadge::whereYear('created_at', date('Y'))->where('is_printed', true)->whereNotNull('batch_number')->with('exhibitor')->get();
        return response()->json($badges);
    }

    public function delegateBadges($event_code){
        $event = SubEvent::where('event_code', $event_code)->first();
        //get badges printed this year
        $badges = Badge::where('sub_event_id', $event->id)->where('is_printed', true)->get();
        return response()->json($badges);
    }

    public function visitorBadges($event_code){
        $event = Event::where('event_code', $event_code)->first();
        $badge_type = BadgeType::where('name', 'Visitor')->first();
        $badges = Badge::where('event_id', $event->id)->where('badge_type_id', $badge_type->id)->where('is_printed', true)->with('event')->get();

        return response()->json($badges);
    }
}

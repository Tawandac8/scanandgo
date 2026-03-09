<?php

namespace App\Http\Controllers;

use App\Models\ExhibitorBadge;
use App\Models\Badge;
use App\Models\SubEvent;
use Illuminate\Http\Request;

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
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Models\BadgeType;
use App\Models\Badge;


class ReportController extends Controller
{
    function events(){
        $response = json_decode(Http::withoutVerifying()->acceptJson()->get('https://www.zitfevents.com/api/v1/get-all-events/'));

            foreach($response[0] as $event){
                $exhisting_event = Event::where('event_code',$event->event_code)->first();

                if(!$exhisting_event){
                    Event::create([
                        'name' => $event->name,
                        'year' => $event->year,
                        'start_date' => $event->start_date,
                        'end_date' => $event->end_date,
                        'event_code' => $event->event_code,
                    ]);
            }else{
                $exhisting_event->update([
                    'name' => $event->name,
                    'year' => $event->year,
                    'start_date' => $event->start_date,
                    'end_date' => $event->end_date,
                ]);
            }
        }

        $events = Event::orderBy('year','DESC')->get();

        return view('reports.events', ['events' => $events]);
    }

    function report($event){
        $event = Event::where('id', $event)->first();
        //get visitor badge type
        $visitor_badge = BadgeType::where('name','Visitor')->first();
        //seggregate the badges by date
        $badges = [];
        $countries = [];
        //count the number of days
        $start_date = Carbon::parse($event->start_date);
        $end_date = Carbon::parse($event->end_date);
        $number_of_days = $start_date->diffInDays($end_date);

        $total_visitors = Badge::where('event_id', $event->id)->where('badge_type_id', $visitor_badge->id)->where('is_printed', 1)->where('printed_date','>=', $start_date->format('Y-m-d'))->count();
        $all_visitors = Badge::where('event_id', $event->id)->where('badge_type_id', $visitor_badge->id)->where('is_printed', 1)->where('printed_date','>=', $start_date->format('Y-m-d'))->get();
        //visitors by date
        for($i = 0;$i <= $number_of_days; $i++){
            $date = $start_date->copy()->addDays($i);
            array_push($badges, [$date->format('Y-m-d') => Badge::where('event_id', $event->id)->where('badge_type_id', $visitor_badge->id)->where('printed_date', $date->format('Y-m-d'))->count()]);
        }
        //visitors by country
        foreach($all_visitors as $visitor){
            if($visitor->country == null || $visitor->country == ''){
                continue;
            }
            
            if(!in_array($visitor->country, $countries)){
                array_push($countries, $visitor->country);
            }
        }
        
        return view('reports.report', ['event' => $event, 'badges' => $badges,'total_visitors'=>$total_visitors,'countries'=>$countries]);

    }
}

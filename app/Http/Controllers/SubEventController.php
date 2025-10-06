<?php

namespace App\Http\Controllers;

use App\Models\SubEvent;
use Illuminate\Http\Request;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use App\Models\Badge;
use App\Models\BadgeType;

class SubEventController extends Controller
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

        $events = Event::where('start_date','>=',Carbon::now()->format('Y-m-d'))->orderBy('start_date','ASC')->get();

        return view('delegates.events', ['events' => $events]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index($event)
    {
        $event = Event::where('event_code', $event)->first();

        $response = Http::withoutVerifying()->acceptJson()->get('https://www.zitfevents.com/api/v1/concurrent-events/'.$event->event_code);

        $concurrent_events = $response->json($key = 'data');

        foreach($concurrent_events as $concurrent_event){
            $event_exists = SubEvent::where('name',$concurrent_event['name'])->where('year', $concurrent_event['event']['year'])->first();

            if($event_exists) continue;
            
            SubEvent::create([
                'name' => $concurrent_event['name'],
                'year' => $event->year,
                'start_date' => ($concurrent_event['multiday_event'])? $concurrent_event['start_date'] : $concurrent_event['date'],
                'end_date' => ($concurrent_event['multiday_event'])? $concurrent_event['end_date'] : null,
                'year' => $concurrent_event['event']['year'],
                'event_id' => $event->id,
            ]);
        }

        $events = SubEvent::where('event_id', $event->id)->get();

        return view('delegates.event',['events'=>$events,'event'=>$event]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($event)
    {
            //fetch sub event
        $event = SubEvent::where('id', $event)->with('event')->first();
            //fetch registrants
            $response = Http::withoutVerifying()->acceptJson()->get('https://www.zitfevents.com/api/v1/'.$event->event->event_code.'/delegates');

            $events = $response->json($key = 'data');

            //delegate badge type
            $delegate_badge = BadgeType::where('name','Delegate')->first();

            foreach($events as $sub_event){
                if($sub_event['name'] != $event->name) continue;

                foreach($sub_event['delegates']as $registrant){
                    $delegate_exists = Badge::where('sub_event_id',$event->id)->where('reg_code', $registrant['registration_code'])->first();
                    if(!$delegate_exists && $registrant['status']['name'] != 'Pending Payment'){
                        Badge::create([
                            'event_id' => $event->event->id,
                            'sub_event_id' => $event->id,
                            'name' => $registrant['title'].' '.$registrant['full_name'],
                            'position' => $registrant['designation'],
                            'company_name' => $registrant['company'],
                            'city' => ($registrant['city'])? $registrant['city']['name']:'',
                            'country' => ($registrant['country'])? $registrant['country']['name'] : '',
                            'reg_code' => $registrant['registration_code'],
                            'badge_type_id' => $delegate_badge->id,
                        ]);
                    }
                    
                }
            }

            $badges = Badge::where('sub_event_id',$event->id)->paginate(25);

            

            return view('delegates.index', ['badges' => $badges, 'event' => $event]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubEvent $subEvent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubEvent $subEvent)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubEvent $subEvent)
    {
        //
    }
}

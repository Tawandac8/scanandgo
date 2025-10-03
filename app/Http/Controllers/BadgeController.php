<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\BadgeType;


class BadgeController extends Controller
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

        return view('otherBadges.events', ['events' => $events]);
    }

    
    /**
     * Display a listing of the resource.
     */
    public function index($event)
    {
        $event = Event::where('id',$event)->first();

        $visitor_badge = BadgeType::where('name','Visitor')->first();

        $delegate_badge = BadgeType::where('name','Delegate')->first();

        $badges = Badge::where('event_id',$event->id)->where('badge_type_id','!=',$visitor_badge->id)->paginate(30);

        $badge_types = BadgeType::all();

        return view('otherBadges.badges', ['badges' => $badges,'event'=>$event,'types'=>$badge_types]);
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
        //create a random 10 digit number
        $random = substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 10);
        //create registration code
        $registration_code = date("Y").$random;

        //create badge
        $badge = Badge::create([
            'name' => $request->name,
            'company_name' => $request->company,
            'badge_type_id' => $request->badge_type,
            'event_id' => $request->event,
            'reg_code' => $registration_code,
        ]);

        $output = '';
        $output = '<div id="badge" class="card-body pb-0">';

        if($badge->name){
            $output .= '<h4 class="badge-name">'.$badge->name.'</h4>';
        }else{
            $output .= '<h4 class="badge-name">'.$badge->company_name.'</h4>';
        }
        
        if($badge->company_name && $badge->name){
            $output .= '<p class="text-sm text-dark">';

            $output .= $badge->company_name.'</p>';
        }
              
        $output .= QrCode::generate($registration_code);

        $output .= '</div><div class="card-footer">
              <span onclick="startPrint('.$badge->id.')" class="btn bg-gradient-primary">Print</span>
            </div>';

        return $output;
    }

    /**
     * Display the specified resource.
     */
    public function show($badge)
    {
        $badge = Badge::where('id',$badge)->first();

        $output = '';
        $output = '<div id="badge" class="card-body pb-0">';

        if($badge->name){
            $output .= '<h4 class="badge-name">'.$badge->name.'</h4>';
        }else{
            $output .= '<h4 class="badge-name">'.$badge->company_name.'</h4>';
        }
        
        if($badge->company_name && $badge->name){
            $output .= '<p class="text-sm text-dark">';

            $output .= $badge->company_name.'</p>';
        }
              
        $output .= QrCode::generate($badge->reg_code);

        $output .= '</div><div class="card-footer">
              <span onclick="startPrint('.$badge->id.')" class="btn bg-gradient-primary">Print</span>
            </div>';

        return $output;
    }

    function print($badge){
        $badge = Badge::where('id',$badge)->first();
        $badge->update([
            'is_printed' => 1,
            'printed_copies' => $badge->printed_copies + 1
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Badge $badge)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Badge $badge)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Badge $badge)
    {
        //
    }
}

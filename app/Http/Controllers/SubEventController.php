<?php

namespace App\Http\Controllers;

use App\Models\SubEvent;
use Illuminate\Http\Request;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use App\Models\Badge;
use App\Models\BadgeType;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BadgeExport;

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

        $events = Event::where('end_date','>=',Carbon::now()->format('Y-m-d'))->orderBy('start_date','ASC')->get();

        return view('delegates.events', ['events' => $events]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index($event)
    {
        $event = Event::where('event_code', $event)->first();

        $api_key = config('services.skylon.api_key');
        $response = Http::withoutVerifying()->withToken($api_key)->acceptJson()->get('https://www.myskylon.com/api/sub-events/event/'.$event->event_code);
        $sub_events = $response->json();
        if(count($sub_events['data']) > 0){
        //loop through exhibitors
        foreach($sub_events['data'] as $sub_event){
            //check if exhibitor exists
            $event_exists = SubEvent::where('event_code',$sub_event['event_code'])->first();
            //if not, create exhibitor
            if(!$event_exists){
                SubEvent::create([
                    'event_id' => $event->id,
                    'name' => $sub_event['name'],
                    'start_date' => $sub_event['start_date'],
                    'end_date' => $sub_event['end_date'],
                    'year' => $sub_event['year'],
                    'event_code' => $sub_event['event_code']
                ]);
                
            }else{
                $event_exists->update([
                    'name' => $sub_event['name'],
                    'start_date' => $sub_event['start_date'],
                    'end_date' => $sub_event['end_date'],
                    'year' => $sub_event['year'],
                    'event_code' => $sub_event['event_code']
                ]);
            } 
        }
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
            $api_key = config('services.skylon.api_key');
            $response = Http::withoutVerifying()->withToken($api_key)->acceptJson()->get('https://www.myskylon.com/api/sub-events/'.$event->event_code.'/printed-delegates');

            $delegates = $response->json($key = 'data');

            //delegate badge type
            $delegate_badge = BadgeType::where('name','Delegate')->first();

            foreach($delegates as $delegate){
                $delegate_exists = Badge::where('sub_event_id',$event->id)->where('reg_code', $delegate['reg_code'])->first();
                if(!$delegate_exists){
                    Badge::create([
                        'event_id' => $event->event->id,
                        'sub_event_id' => $event->id,
                        'name' =>$delegate['full_name'],
                        'position' => $delegate['position'],
                        'company_name' => $delegate['company_name'],
                        'reg_code' => $delegate['reg_code'],
                        'badge_type_id' => $delegate_badge->id,
                    ]);
                }
            }

            $badges = Badge::where('sub_event_id',$event->id)->where('is_printed',0)->where('badge_type_id',$delegate_badge->id)->orderBy('created_at','desc')->paginate(25);

            return view('delegates.index', ['badges' => $badges, 'event' => $event]);
    }

/**
 * Search for delegates based on the given query string.
 *
 * @param Request $request
 * @return mixed
 */
    function search_delegates(Request $request){
        //get delegate badge type
        $delegate_badge = BadgeType::where('name','Delegate')->first();

        $event = SubEvent::where('id',$request->get('event_id'))->first();
       
        //search delegates
        $badges = Badge::where('sub_event_id',$event->id)->where('badge_type_id',$delegate_badge->id)->where('name','like','%'.$request->query.'%')->get();
        return $badges;

        $output = '';

        foreach($badges as $badge){

            $output .= '<tr>
                <td>
                    <div class="d-flex px-2 py-1">
                        <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">'.$badge->name.'}}</h6>
                        </div>
                    </div>
                </td>
                    <td>
                        <span class="text-xs font-weight-bold">'.$badge->company_name.'</span>
                    </td>
                    <td>
                        <span class="text-xs font-weight-bold">'.$badge->badge_type->name.'</span>
                    </td>
                    <td>';
                    if($badge->is_printed){
                         $output .= '<span class="badge badge-sm bg-gradient-success">Yes</span>';
                    }else{
                        $ouput .= '<span class="badge badge-sm bg-gradient-danger">No</span>';
                    }

            $output .= '</td>
                <td class="align-middle text-center text-sm">
                    <span class="text-xs font-weight-bold"> {{ $badge->printed_copies }} </span>
                </td>
                <td>
                    <span onclick="viewBadge({{ $badge->id }})" style="cursor: pointer" class="badge badge-sm bg-gradient-info">Go <i class="fa-solid fa-arrow-right"></i></span>
                </td>
            </tr>';
                        
        }

        return $output;
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
    public function destroy($subEvent)
    {
        SubEvent::where('id', $subEvent)->delete();
        return redirect()->back()->with('success', 'Sub Event deleted successfully');
    }

    public function destroyDelegates($subEvent)
    {
        $badge_type = BadgeType::where('name','Delegate')->first();

        $badges = Badge::where('badge_type_id', $badge_type->id)->where('is_printed',0)->get();
        foreach($badges as $badge){
            $badge->delete();
        }
        return redirect()->back()->with('success', 'Delegates deleted successfully');
    }

    public function export($id){
        $event = SubEvent::where('id', $id)->first();

        return Excel::download(new BadgeExport($event), $event->name.' '.$event->year.' '.'delegates.xlsx');
    }
}

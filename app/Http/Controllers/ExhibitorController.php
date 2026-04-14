<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Exhibitor;
use App\Models\ExhibitorBadge;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class ExhibitorController extends Controller
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

        $events = Event::orderBy('start_date','DESC')->get();

        return view('exhibitors.event', ['events' => $events]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index($event)
    {
        $event = Event::where('id',$event)->first();
        $all_exhibitors = Exhibitor::where('event_code',$event->event_code)->paginate(30);

        return view('exhibitors.index', ['exhibitors' => $all_exhibitors,'event'=>$event]);
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
    public function show(Exhibitor $exhibitor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Exhibitor $exhibitor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Exhibitor $exhibitor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($exhibitor)
    {
        $exhibitor = Exhibitor::where('id',$exhibitor)->first();
        $exhibitor->delete();
        return back();
    }

    //search exhibitor
    public function search(Request $request){
        $q = $request->q;
        $exhibitors = Exhibitor::where('company_name','like','%'.$q.'%')->paginate(25);
        
        $output = '';

        foreach($exhibitors as $exhibitor){
            $output .= '<tr>
                      <td>
                        <input type="checkbox" class="exhibitor-checkbox ms-3" value="'.$exhibitor->id.'">
                      </td>
                      <td>
                        <span class="text-xs font-weight-bold"> '.$exhibitor->created_at.' </span>
                      </td>
                      <td>
                        <div class="d-flex px-2 py-1">
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">'.$exhibitor->company_name.'</h6>
                          </div>
                        </div>
                      </td>
                      <td>
                        <span class="text-xs font-weight-bold">  </span>
                      </td>
                      <td>
                        <span class="text-xs font-weight-bold">  </span>
                      </td>
                      <td>
                        <a href="'.route('exhibitor.badge.export',$exhibitor->id) .'" class="badge badge-sm bg-gradient-success">Export Printed</a>
                        <a href="'.route('exhibitor.badges.index',$exhibitor->id) .'" class="badge badge-sm bg-gradient-info">Go <i class="fa-solid fa-arrow-right"></i></a>
                      </td>
                    </tr>';
        }
        return $output;
    }

    public function bulkDestroy(Request $request)
    {
        if (!Auth::user()->hasRole('super-admin')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $ids = $request->ids;
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No exhibitors selected'], 400);
        }

        Exhibitor::whereIn('id', $ids)->delete();

        return response()->json(['success' => true, 'message' => 'Selected exhibitors deleted successfully']);
    }

    function printedBadges($event){
        $event = Event::where('id',$event)->first();

        $printedBadges = [];
        $exhibitorBadges = [];
        $exhibitorComp = [];
        $attendant = [];

        $exhibitors = Exhibitor::where('event_code',$event->event_code)->get();
        foreach($exhibitors as $exhibitor){
            $exhibitorBadges = ExhibitorBadge::where('exhibitor_id',$exhibitor->id)->where('is_printed',1)->get();
            
            foreach($exhibitorBadges as $badge){
                $printedBadges[] = $badge;

                if($badge->badge_type->name == 'Exhibitor'){
                    $exhibitorBadges[] = $badge;
                }
                if($badge->badge_type->name == 'Attendant'){
                    $attendant[] = $badge;
                }
                if($badge->badge_type->name == 'Exhibitor Comp'){
                    $exhibitorComp[] = $badge;
                }
            }
        }

        // Paginate the array
    $page = request()->get('page', 1);
    $perPage = 30;
    $offset = ($page - 1) * $perPage;
    $paginatedBadges = new LengthAwarePaginator(
        array_slice($printedBadges, $offset, $perPage),
        count($printedBadges),
        $perPage,
        $page,
        ['path' => request()->url(), 'query' => request()->query()]
    );

        return view('exhibitors.printed', ['badges' => $paginatedBadges,'all_badges'=>$printedBadges,'event'=>$event,'comp'=>$exhibitorComp,'attendant'=>$attendant,'exhibitorBadges'=>$exhibitorBadges]);
    }
}

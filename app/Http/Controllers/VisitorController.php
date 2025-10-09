<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BadgeType;
use App\Models\Country;
use App\Models\Badge;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Event;
use Carbon\Carbon;

class VisitorController extends Controller
{
    /**
     * Display a list of business visitors.
     *
     * This function retrieves a list of countries and visitors based on the user's role.
     * If the user is a 'super-admin' or 'admin', it fetches all visitors with the 'Visitor'
     * badge type. Otherwise, it fetches visitors associated with the user's event.
     * It returns a view with the list of visitors and countries.
     *
     * @return \Illuminate\View\View
     */

    function index($event){
        $event = Event::where('id',$event)->first();

             $badge_type = BadgeType::where('name','Visitor')->first();


            $visitors = Badge::where('event_id',$event->id)->where('badge_type_id',$badge_type->id)->orderBy('created_at','desc')->paginate(30);

            $countries = Country::all();
        
        return view('visitors.index',['visitors'=>$visitors,'countries'=>$countries,'event'=>$event]);
    }

    function print($id){
        $visitor = Badge::where('id', $id)->first();
        //update printed date
        if($visitor->is_printed == 0){
            $visitor->printed_date = Carbon::now()->format('Y-m-d');
        }

        $visitor->user_id = Auth::user()->id;
        
        $visitor->is_printed = 1;
        $visitor->printed_copies++;
        $visitor->save();
    }

    /**
     * Adds a new visitor to the database
     * @param Request $request
     * @return string
     */
    function addVisitor(Request $request){
        //create a random 10 digit number
        $random = substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 10);
        //create registration code
        $registration_code = date("Y").$random;
        //fetch visitor badge
        $visitor_badge = BadgeType::where('name', 'Visitor')->first();
        //create visitor
        $visitor = Badge::create([
            'badge_type_id'=>$visitor_badge->id,
            'title'=>$request->title,
            'first_name'=>$request->first_name,
            'last_name'=>$request->last_name,
            'reg_code'=>$registration_code,
            'company_name'=>$request->company,
            'position'=>$request->position,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'country_id'=>$request->country,
            'receipt_number'=>$request->receipt
        ]);

        $output = '';
        $output = '<div id="badge" class="card-body pb-0">';
        $output .= '<h4 class="badge-name">'.$visitor->title.' '.$visitor->first_name.' '.$visitor->last_name.'</h4>
              <p class="text-sm text-dark">';
              if($visitor->position){
                $output .= $visitor->position.' <br>';
              }
                
        $output .= $visitor->company_name.'</p>';
        $output .= QrCode::generate($visitor->reg_code);

        $output .= '</div><div class="card-footer">
              <span onclick="startPrint('.$visitor->id.')" class="btn bg-gradient-primary">Print</span>
            </div>';

        return $output;
    }

    /**
     * Displays a visitor badge given a visitor id
     * @param int $id
     * @return string
     */
    function viewVisitor($id){
        $visitor = Badge::where('id',$id)->first();

        $output = '';
        $output = '<div id="badge" class="card-body pb-0">';
        $output .= '<h4 class="badge-name">'.$visitor->title.' '.$visitor->first_name.' '.$visitor->last_name.'</h4>
              <p class="text-sm text-dark">';
              if($visitor->position){
                $output .= $visitor->position.' <br>';
              }
                
        $output .= $visitor->company_name.'</p>';
        $output .= QrCode::generate($visitor->reg_code);

        $output .= '</div><div class="card-footer">
              <span onclick="startPrint('.$visitor->id.')" class="btn bg-gradient-primary">Print</span>
            </div>';

        return $output;
        
    }

    /**
     * Searches for a visitor with a given registration code
     * @param Request $request
     * @return string
     * 
     * This function searches for a visitor with the given registration code.
     * If the visitor is found, it displays a badge with the visitor's information.
     * If the visitor is not found, it returns a string 'false'.
     * 
     * The badge is a div with the id "badge" and it contains the visitor's title, first name, last name,
     * position (if any), company name and registration code.
     * The registration code is displayed as a QR code.
     * The badge also has a print button which triggers the startPrint function when clicked.
     */
    function searchVisitor(Request $request){

            $visitor = Badge::where('reg_code', $request->q)->first();
            if($visitor){
            $output = '';
            $output = '<div id="badge" class="card-body pb-0">';
            $output .= '<h4 class="badge-name">'.$visitor->title.' '.$visitor->first_name.' '.$visitor->last_name.'</h4>
                <p class="text-sm text-dark">';
                if($visitor->position){
                $output .= $visitor->position.' <br>';
                }
                
            $output .= $visitor->company_name.'</p>';
            $output .= QrCode::generate($visitor->reg_code);

            $output .= '</div><div class="card-footer">
                <span onclick="startPrint('.$visitor->id.')" class="btn bg-gradient-primary">Print</span>
                </div>';

            return $output;
        }

        return 'false';
    }

    function searchVisitorName(Request $request){
        $visitors = Badge::where('name', 'like', '%'.$request->name.'%')->get();
        
        $output = '';

        foreach($visitors as $visitor){
            $output .= '<tr>
                      <td>
                        <div class="d-flex px-2 py-1">
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">'.$visitor->name.'</h6>
                          </div>
                        </div>
                      </td>
                      <td>
                        <span class="text-xs font-weight-bold">'.$visitor->company_name.'</span>
                      </td>
                      <td class="align-middle text-center text-sm">
                        <span class="text-xs font-weight-bold">'.$visitor->position.'</span>
                      </td>
                      <td class="text-center">';

                        if(!$visitor->is_printed){
                            $output .= '<span class="text-xs font-weight-bold text-danger"> <i class="fa-solid fa-xmark"></i> </span>';
                        }else{
                            $output .= '<span class="text-xs font-weight-bold text-success"> <i class="fa-solid fa-check"></i> </span>';
                        }

                      $output .= '</td>
                      <td>
                        <span style="cursor: pointer" onclick="viewVisitor('. $visitor->id .')" class="badge badge-sm bg-gradient-info">Go <i class="fa-solid fa-arrow-right"></i></span>
                      </td>
                    </tr>';
        }

        

        return $output;
    }

    function visitorsEvents(){
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

        return view('visitors.events', ['events' => $events]);
    }

    function deleteEventVisitors($event){
        $event = Event::where('id',$event)->first();

        $badge_type = BadgeType::where('name','Visitor')->first();

        Badge::where('event_id',$event->id)->where('badge_type_id',$badge_type->id)->delete();

        return redirect()->back()->with('success', 'All visitors for '.$event->name.' have been deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BadgeType;
use App\Models\Country;
use App\Models\Badge;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Auth;

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

    function index(){
        //get countries
        $countries = Country::orderBy('name', 'asc')->get();
        //get all visitors from badge type visitor

        $visitor_badge = BadgeType::where('name', 'Visitor')->with('badges')->first();
        if(empty($visitor_badge)){
            return view('visitors.index',['countries'=>$countries]);
        }

        $role = Auth::user()->getRoleNames()[0];

        if($role == 'super-admin' || $role == 'admin'){
            $visitors = Badge::where('badge_type_id', $visitor_badge->id)->with('country')->get();
            return view('visitors.index',['visitors'=>$visitors,'countries'=>$countries]);
        }

        $visitors = Badge::where('event_id', Auth::user()->event_id)->where('badge_type_id', $visitor_badge->id)->with('country')->get();
        
        return view('visitors.index',['visitors'=>$visitors,'countries'=>$countries]);
    }

    function print($id){
        $visitor = Badge::where('id', $id)->first();

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
            'country_id'=>$request->country
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
}

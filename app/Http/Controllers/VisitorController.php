<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BadgeType;
use App\Models\Country;
use App\Models\Badge;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class VisitorController extends Controller
{
    function index(){
        //get countries
        $countries = Country::orderBy('name', 'asc')->get();
        //get all visitors from badge type visitor

        $visitor_badge = BadgeType::where('name', 'Visitor')->with('badges')->first();
        if(empty($visitor_badge)){
            return view('visitors.index',['countries'=>$countries]);
        }
        $visitors = Badge::where('badge_type_id', $visitor_badge->id)->get();
        
        return view('visitors.index',['visitors'=>$visitors,'countries'=>$countries]);
    }

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
}

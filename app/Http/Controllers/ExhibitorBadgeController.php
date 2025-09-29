<?php

namespace App\Http\Controllers;

use App\Models\ExhibitorBadge;
use App\Models\Exhibitor;
use App\Models\BadgeType;
use Illuminate\Support\Facades\Http;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Request;

class ExhibitorBadgeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($exhibitor)
    {
        $exhibitor = Exhibitor::where('id', $exhibitor)->first();

        $response = Http::withoutVerifying()->acceptJson()->get('https://www.zitfevents.com/api/v1/exhibitor-badges/'.$exhibitor->code);

        $exhibitor_badges = $response->json($key = 'data');

        foreach($exhibitor_badges as $badge){
            
            $existing_badge = ExhibitorBadge::where('name',$badge['name'])->where('exhibitor_id',$exhibitor->id)->first();

            if(!$existing_badge){
                $badge_type = BadgeType::where('name', $badge['badge_type']['name'])->first();
                ExhibitorBadge::create([
                    'name' => $badge['name'],
                    'exhibitor_id' => $exhibitor->id,
                    'badge_type_id' => $badge_type->id,
                ]);
            }
        }

        $badges = ExhibitorBadge::where('exhibitor_id',$exhibitor->id)->paginate(25);

        return view('exhibitors.badges',[ 'badges' => $badges, 'exhibitor' => $exhibitor ]);
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
    public function show($badge)
    {
        $badge = ExhibitorBadge::where('id',$badge)->with('exhibitor')->first();
        
        $output = '';
        $output = '<div id="badge" class="card-body pb-0">';
        $output .= '<h4 class="badge-name">'.$badge->name.'</h4>
              <p class="text-sm text-dark">';
                
        $output .= $badge->exhibitor->company_name.'</p>';
        $output .= QrCode::generate('https://www.zitfevents.com/marketplace/scanned-exhibitor/'.$badge->exhibitor->code);

        $output .= '</div><div class="card-footer">
              <span onclick="startPrint('.$badge->id.')" class="btn bg-gradient-primary">Print</span>
            </div>';

        return $output;
    }

    function print($badge){
        $badge = ExhibitorBadge::where('id',$badge)->with('exhibitor')->first();
        $badge->update([
            'is_printed' => true,
            'printed_copies' => $badge->printed_copies + 1
        ]);
        
        }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ExhibitorBadge $exhibitorBadge)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ExhibitorBadge $exhibitorBadge)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExhibitorBadge $exhibitorBadge)
    {
        //
    }
}

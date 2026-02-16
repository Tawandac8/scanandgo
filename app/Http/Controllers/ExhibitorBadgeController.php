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
        $badges = ExhibitorBadge::where('exhibitor_id',$exhibitor->id)->paginate(25);
        dd($badges);
        $badge_types = BadgeType::all();

        return view('exhibitors.badges',[ 'badges' => $badges, 'exhibitor' => $exhibitor,'types'=>$badge_types]);
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
    public function store(Request $request, $exhibitor)
    {

        $exhibitor = Exhibitor::where('id',$exhibitor)->first();

        ExhibitorBadge::create([
            'name' => $request->name,
            'exhibitor_id' => $exhibitor->id,
            'badge_type_id' => $request->badge_type,
        ]);

        return redirect()->back()->with('success', 'Badge added successfully.');
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

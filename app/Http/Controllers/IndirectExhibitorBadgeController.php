<?php

namespace App\Http\Controllers;

use App\Models\IndirectExhibitorBadge;
use App\Models\IndirectExhibitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class IndirectExhibitorBadgeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndirectExhibitor $exhibitor)
    {
       
        // $api_key = config('services.skylon.api_key');
        // $response = Http::withoutVerifying()->withToken($api_key)->acceptJson()->get('https://www.myskylon.com/api/v1/indirect-exhibitor-badges/'.$exhibitor->code);

        // $api_badges = $response->json();

        // dd($response);

        // //loop through badges
        // foreach($api_badges as $badge){
        //     //check if badge exists
        //     $badge_exists = IndirectExhibitorBadge::where('indirect_exhibitor_id', $exhibitor->id)->where('batch_number', $badge['batch_number'])->first();
        //     //if not, create badge
        //     if(!$badge_exists){
        //         IndirectExhibitorBadge::create([
        //             'name' => $badge['name'],
        //             'indirect_exhibitor_id' => $exhibitor->id,
        //             'badge_type_id' => $badge['badge_type_id'],
        //             'batch_number' => $badge['batch_number'],
        //         ]);
        //     }
        // }

        $badges = IndirectExhibitorBadge::where('indirect_exhibitor_id', $exhibitor->id)->get();
        return view('exhibitors.indirect.badges', [
            'exhibitor' => $exhibitor,
            'badges' => $badges
        ]);
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
    public function show(IndirectExhibitorBadge $badge)
    {
        $badge = IndirectExhibitorBadge::where('id',$badge)->with('indirectExhibitor')->first();

        $output = '';
        $output = '<div id="badge" class="card-body pb-0">';
        $output .= '<h4 class="badge-name">'.$badge->name.'</h4>
              <p class="text-sm text-dark">';

        $output .= $badge->indirectExhibitor->company_name.'</p>';
        $output .= QrCode::generate('https://www.zitfevents.com/marketplace/scanned-exhibitor/'.$badge->indirectExhibitor->code);

        $output .= '</div><div class="card-footer">
              <span onclick="startPrint('.$badge->id.')" class="btn bg-gradient-primary">Print</span>
            </div>';

        return $output;
    }

    function print($badge){
        $badge = IndirectExhibitorBadge::where('id',$badge)->with('indirectExhibitor')->first();
        $badge->update([
            'is_printed' => true,
            'printed_count' => $badge->printed_count + 1,
            'printed_in_bulawayo' => 1,
            'printed_by' => Auth::user()->name,
            'printed_at' => date('Y-m-d H:i:s'),
        ]);

        }

    public function updateSerialNumber(Request $request, $badge)
    {
        $badge = IndirectExhibitorBadge::where('id',$badge)->first();
        $badge->update([
            'serial_number' => $request->serial_number,
            'printed_copies' => $badge->printed_copies + 1,
            'printed_in_bulawayo' => 1,
            'printed_by' => Auth::user()->name,
            'printed_date' => date('Y-m-d H:i:s'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Serial number updated successfully.',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(IndirectExhibitorBadge $indirectExhibitorBadge)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, IndirectExhibitorBadge $indirectExhibitorBadge)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IndirectExhibitorBadge $indirectExhibitorBadge)
    {
        //
    }
}

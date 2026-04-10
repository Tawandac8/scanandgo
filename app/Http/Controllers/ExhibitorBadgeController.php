<?php

namespace App\Http\Controllers;

use App\Models\ExhibitorBadge;
use App\Models\Exhibitor;
use App\Models\BadgeType;
use Illuminate\Support\Facades\Http;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exports\ExportExhibitorBadges;
use Maatwebsite\Excel\Facades\Excel;

class ExhibitorBadgeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($exhibitor)
    {
        $exhibitor = Exhibitor::where('id', $exhibitor)->first();
        //
        $api_key = config('services.skylon.api_key');
        $response = Http::withoutVerifying()->withToken($api_key)->acceptJson()->get('https://www.myskylon.com/api/v1/exhibitor-badges/'.$exhibitor->code);
            
            $exhibitor_badges = $response->json();
            //loop through exhibitor badges
            foreach($exhibitor_badges as $exhibitor_badge){
                //get badge type id
                $badge_type_id = BadgeType::where('name',$exhibitor_badge['badge_type']['name'])->first()->id;
            //check if exhibitor badge exists
            $exhibitor_badge_exists = ExhibitorBadge::where('exhibitor_id',$exhibitor->id)->where('batch_number',$exhibitor_badge['batch_number'])->first();
            //if not, create exhibitor badge
            if(!$exhibitor_badge_exists){
                ExhibitorBadge::create([
                    'name' => $exhibitor_badge['name'],
                    'exhibitor_id' => $exhibitor->id,
                    'badge_type_id' => $badge_type_id,
                    'batch_number' => $exhibitor_badge['batch_number'],
                ]);
            }else{
                $exhibitor_badge_exists->name = $exhibitor_badge['name'];
                if($exhibitor_badge['is_printed'] == 1){
                    $exhibitor_badge_exists->printed_copies = $exhibitor_badge['printed_quantity'];
                    $exhibitor_badge_exists->is_printed = 1;
                    $exhibitor_badge_exists->printed_by = $exhibitor_badge['printed_by'];
                    $exhibitor_badge_exists->printed_date = $exhibitor_badge['printed_at'];

                }
                $exhibitor_badge_exists->save();
            }
        }
        //
        $badges = ExhibitorBadge::where('exhibitor_id',$exhibitor->id)->where('is_printed',0)->paginate(25);
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
            'printed_copies' => $badge->printed_copies + 1,
            'printed_in_bulawayo' => 1,
            'printed_by' => Auth::user()->name,
            'printed_date' => date('Y-m-d H:i:s'),
        ]);

        }

    public function updateSerialNumber(Request $request, $badge)
    {
        $badge = ExhibitorBadge::where('id',$badge)->first();
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

    function getPrintedBadges($exhibitor){
        $exhibitor = Exhibitor::where('id',$exhibitor)->first();
        $badges = ExhibitorBadge::where('exhibitor_id',$exhibitor->id)->where('is_printed',1)->paginate(25);
        return view('exhibitors.printed-badges', compact('badges','exhibitor'));
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

    public function getBadgeData($badge)
    {
        $badge = ExhibitorBadge::where('id', $badge)->first();
        return response()->json($badge);
    }

    public function updateBadgeData(Request $request, $badge)
    {
        $badge = ExhibitorBadge::where('id', $badge)->first();
        $badge->update([
            'name' => $request->name,
            'badge_type_id' => $request->badge_type_id,
            'is_printed' => $request->is_printed,
            'printed_copies' => $request->printed_copies,
            'serial_number' => $request->serial_number,
            'printed_by' => $request->printed_by,
            'printed_date' => $request->printed_date,
            'printed_in_bulawayo' => $request->printed_in_bulawayo ? 1 : 0,
        ]);

        return response()->json(['success' => true]);
    }

    public function exportByExhibitor($exhibitor)
    {
        $exhibitor = Exhibitor::findOrFail($exhibitor);
        $badges = ExhibitorBadge::where('exhibitor_id', $exhibitor->id)->where('is_printed', 1)->with(['exhibitor', 'badge_type'])->get();
        return Excel::download(new ExportExhibitorBadges($badges), $exhibitor->company_name . ' Printed Badges.xlsx');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExhibitorBadge $exhibitorBadge)
    {
        //
    }
}

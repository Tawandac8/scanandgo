<?php

namespace App\Http\Controllers;

use App\Models\IndirectExhibitor;
use Illuminate\Http\Request;
use App\Models\Exhibitor;
use App\Models\IndirectExhibitorBadge;
use Illuminate\Support\Facades\Http;

class IndirectExhibitorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Exhibitor $exhibitor)
    {
        //fetch indirect exhibitors for the exhibitor
        $api_key = config('services.skylon.api_key');
        $response = Http::withoutVerifying()->withToken($api_key)->acceptJson()->get('https://www.myskylon.com/api/v1/indirect-exhibitors/'.$exhibitor->code);

         $exhibitors = $response->json();

         foreach($exhibitors as $indirect_exhibitor){
            //check if exhibitor exists
            $exhibitor_exists = IndirectExhibitor::where('code',$indirect_exhibitor['exhibitor_code'])->first();
            //if not, create exhibitor
            if(!$exhibitor_exists){
                IndirectExhibitor::create([
                    'code' => $indirect_exhibitor['exhibitor_code'],
                    'company_name' => $indirect_exhibitor['company_name'],
                    'exhibitor_id' => $exhibitor->id
                ]);
                
            }else{
                $exhibitor_exists->update([
                    'company_name' => $indirect_exhibitor['company_name']
                ]);
            }

            if($indirect_exhibitor['indirect_exhibitor_badges']){
                foreach($indirect_exhibitor['indirect_exhibitor_badges'] as $badge){
                    //check if badge exists
                    $badge_exists = IndirectExhibitorBadge::where('indirect_exhibitor_id', $indirect_exhibitor->id)->where('batch_number', $badge['batch_number'])->first();
                    //if not, create badge
                    if(!$badge_exists){
                        IndirectExhibitorBadge::create([
                            'name' => $badge['name'],
                            'indirect_exhibitor_id' => $indirect_exhibitor->id,
                            'badge_type_id' => $badge['badge_type_id'],
                            'batch_number' => $badge['batch_number'],
                        ]);
                    }
                }
            }
        }

        $indirect_exhibitors = IndirectExhibitor::where('exhibitor_id', $exhibitor->id)->get();
        
        return view('exhibitors.indirect.index', compact('exhibitor', 'indirect_exhibitors'));
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
    public function show(IndirectExhibitor $indirectExhibitor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(IndirectExhibitor $indirectExhibitor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, IndirectExhibitor $indirectExhibitor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IndirectExhibitor $indirectExhibitor)
    {
        //
    }
}

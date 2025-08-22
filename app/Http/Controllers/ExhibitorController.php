<?php

namespace App\Http\Controllers;

use App\Models\Exhibitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ExhibitorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $exhibitors = json_decode(Http::withoutVerifying()->acceptJson()->get('http://192.168.0.253/api/v1/exhibitors'));

        dd($exhibitors);

        $exhibitors = Exhibitor::all();

        return view('exhibitors.index', ['exhibitors' => $exhibitors]); 
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
    public function destroy(Exhibitor $exhibitor)
    {
        //
    }
}

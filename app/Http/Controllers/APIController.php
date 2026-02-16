<?php

namespace App\Http\Controllers;

use App\Models\ExhibitorBadge;
use Illuminate\Http\Request;

class APIController extends Controller
{
    public function exhibitorBadges(){
        //get badges printed this year
        $badges = ExhibitorBadge::whereYear('created_at', date('Y'))->where('is_printed', true)->whereNotNull('batch_number')->with('exhibitor')->get();
        return response()->json($badges);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Event;

use Illuminate\Http\Request;

class UserController extends Controller
{
    function index(){
        $users = User::all();
        $events = Event::all();

        return view('users.index',['users'=>$users,'events'=>$events]);
    }

    function store(Request $request){
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->user_password = $request->password;
        $user->event_id = $request->event_id;
        $user->save();

        $user->assignRole($request->role);

        return redirect()->back();
    }
}

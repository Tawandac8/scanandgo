<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Event;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Show the list of users.
     *
     * @return \Illuminate\Http\Response
     */
    
    function index(){
        $users = User::all();
        $events = Event::all();

        return view('users.index',['users'=>$users,'events'=>$events]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

    function edit($id){
        $user = User::where('id','=',$id)->first();
        $events = Event::all();

        $output = '';

        $output .= '<div class="card-body pb-0">
            <h4 class="badge-name">Edit User</h4>
              <form action="'.route('users.update',$user->id).'" method="post">
                '.csrf_field().'
              <label class="required" for="">Name</label>
              <div class="mb-3">
                <input name="name" value="'.$user->name.'" type="text" class="form-control" required>
              </div>
              <label class="required" for="">Email</label>
              <div class="mb-3">
                <input name="email" value="'.$user->email.'" type="text" class="form-control" required>
              </div>
              <label class="required" for="">Password</label>
              <div class="mb-3">
                <input name="password" value="'.$user->user_password.'" type="text" class="form-control" required>
              </div>
              <label for="">Role</label>
              <div class="mb-3">
                <select name="role" class="form-select">
                  <option value="'.$user->getRoleNames()[0].'" selected>'.$user->getRoleNames()[0].'</option>
                  <option value="admin">Admin</option>
                  <option value="visitor-registration">Visitor Officer</option>
                  <option value="conference-registration">Conference Registration</option>
                  <option value="badges-office">Badges Office</option>
                </select>
              </div>';

              if(isset($events)){
              $output .= '<label for="">Event</label>
              <div class="mb-3">
                <select name="event"  class="form-select">';

                  foreach ($events as $event){
                  $output .= '<option value="'.$event->id.'">'.$event->name.'</option>';
                  }

                $output .= '</select></div>';
              }

            $output .= '<button type="submit" class="btn bg-gradient-dark w-100">Update User</button>
            </div>
              
            
            </form>';

            return $output;
    }

    function update($id){
        $user = User::where('id','=',$id)->first();
        $user->name = request('name');
        $user->email = request('email');
        $user->password = Hash::make(request('password'));
        $user->user_password = request('password');
        $user->event_id = request('event');
        $user->save();

        $user->removeRole($user->getRoleNames()[0]);
        $user->assignRole(request('role'));

        return redirect()->back();
    }
}

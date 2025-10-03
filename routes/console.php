<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schedule;
use App\Models\Badge;
use App\Models\BadgeType;
use App\Models\Event;
use Carbon\Carbon;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::call(function () {
        $events = Event::where('start_date','>=',Carbon::now()->format('Y-m-d'))->get();

        //$badges = VisitorBadge::where('event_id',$event->current_event_id)->where('online',0)->orderBy('created_at','asc')->paginate(30);
        foreach($events as $event){
            $response = Http::withoutVerifying()->acceptJson()->get('https://www.zitfevents.com/api/v1/badges/'.$event->event_code);

            $badges = $response->json($key = 'data');

            $badge_type = BadgeType::where('name','Visitor')->first();

            foreach($badges as $badge){
                $badge_exists = Badge::where('reg_code',$badge['registration_code'])->first();
                
                if(!$badge_exists){
                        Badge::create([
                            'badge_type' => $badge_type->name,
                            'badge_type_id' => $badge_type->id,
                            'title' => $badge['profile']['title'],
                            'first_name' => $badge['profile']['first_name'],
                            'last_name' => $badge['profile']['last_name'],
                            'company_name' => isset($badge['profile']['company'])? $badge['profile']['company']['name'] : $badge['profile']['student']['school_name'],
                            'reg_code' => $badge['registration_code'],
                            'event' => $event->name,
                            'is_online_registration' => 1,
                            'position' => isset($badge['profile']['company'])? $badge['profile']['company']['department'] : $badge['profile']['student']['level'],
                            'event_id'=> $event->id,
                            'mobile' => $badge['profile']['phone'],
                            'city' => isset($badge['profile']['city'])? $badge['profile']['city']['name'] : '',
                            'country' => isset($badge['profile']['country'])? $badge['profile']['country']['name']:'',
                            'email' => $badge['profile']['user'] ? $badge['profile']['user']['email'] : ''
                        ]);
                    }
            }
        }
        })->everyMinute();

        // $schedule->call(function(){
        //     $response = Http::withoutVerifying()->acceptJson()->get('https://www.zitfevents.com/api/v1/conference/badges');

        //         $events = $response->json($key = 'data');
    
        //         $current_event = Setting::where('id',1)->first();
    
        //         if(count($events)>0){
        //             //loop through events to get  delegate data and save it into database
        //             foreach ( $events as $event) {
        //                 foreach ($event['delegates'] as $item){
        //                     if($item['status']['name'] != 'Pending Payment'){
        //                         //dd($item);
        //                         $delegate = Delegates::where('event_id',$current_event->current_event_id)->where('email',$item['email'])->first();
        //                         if($delegate){
        //                             //update existing delegate
        //                                     $delegate->title = $item['title'];
        //                                     $delegate->full_name = $item['full_name'];
        //                                     $delegate->designation = $item['designation'];
        //                                     $delegate->company = $item['company'];
        //                                     $delegate->address = $item['address'];
        //                                     $delegate->email = $item['email'];
        //                                     $delegate->mobile_1 = $item['mobile_1'];
        //                                     $delegate->mobile_2 = $item['mobile_2'];
        //                                     $delegate->phone =$item['phone'];
        //                                     $delegate->status = $item['status']['name'];
        //                                     $delegate->concurrent_event = $event['name'];
        //                                     $delegate->registration_code = $item['registration_code'];
    
        //                                     $delegate->save();
    
        //                         }else{
        //                                 $new_delegate = Delegates::create([
        //                                     'title' =>$item['title'],
        //                                     'full_name' =>$item['full_name'],
        //                                     'designation' =>$item['designation'],
        //                                     'company' =>$item['company'],
        //                                     'address' =>$item['address'],
        //                                     'email' =>$item['email'],
        //                                     'mobile_1' =>$item['mobile_1'],
        //                                     'mobile_2' =>$item['mobile_2'],
        //                                     'phone' =>$item['phone'],
        //                                     'status' =>$item['status']['name'],
        //                                     'registration_code' =>$item['registration_code'],
        //                                     'event_id' =>$current_event->current_event_id,
        //                                     'printed' => 0
        //                                 ]);
        //                                 if($item['country_id']){
        //                                     $new_delegate->country = $item['country']['name'];
        //                                     $new_delegate->save();
        //                                 }
    
        //                                 if($item['city_id']){
        //                                     $new_delegate->country = $item['country']['name'];
        //                                     $new_delegate->save();
        //                                 }
    
        //                                 $new_delegate->concurrent_event = $event['name'];
        //                                 $new_delegate->save();
        //                         }
        //                     }
        //                 }
        //             }
        //         }
        // })->everyMinute();
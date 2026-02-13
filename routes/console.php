<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schedule;
use App\Models\Badge;
use App\Models\BadgeType;
use App\Models\Event;
use App\Models\Exhibitor;
use App\Models\ExhibitorBadge;
use Carbon\Carbon;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

//Visitors Badges
Schedule::call(function () {
        $events = Event::where('end_date','>=',Carbon::now()->format('Y-m-d'))->get();

        //$badges = VisitorBadge::where('event_id',$event->current_event_id)->where('online',0)->orderBy('created_at','asc')->paginate(30);
        foreach($events as $event){
            $response = Http::withoutVerifying()->acceptJson()->get('https://www.zitfevents.com/api/v1/badges/'.$event->event_code);

            $badges = $response->json($key = 'data');

            $badge_type = BadgeType::where('name','Visitor')->first();

            foreach($badges as $badge){
                $badge_exists = Badge::where('reg_code',$badge['registration_code'])->first();

                if($badge['profile']){
                    $company_name = isset($badge['profile']['company'])? $badge['profile']['company']['name'] : $badge['profile']['student']['school_name'];
                    $position = isset($badge['profile']['company'])? $badge['profile']['company']['department'] : $badge['profile']['student']['level'];
                    $city = isset($badge['profile']['city'])? $badge['profile']['city']['name'] : '';
                    $country = isset($badge['profile']['country'])? $badge['profile']['country']['name'] : '';
                }else{
                    $company_name = $badge['company'];
                    $position = $badge['position'];
                    $city = $badge['city'];
                    $country = $badge['country'];
                }
                
                if(!$badge_exists){
                        Badge::create([
                            'badge_type' => $badge_type->name,
                            'badge_type_id' => $badge_type->id,
                            'title' => ($badge['profile'])? $badge['profile']['title'] : $badge['title'],
                            'first_name' => ($badge['profile'])? $badge['profile']['first_name'] : $badge['first_name'],
                            'last_name' => ($badge['profile'])? $badge['profile']['last_name'] : $badge['last_name'],
                            'company_name' => $company_name,
                            'reg_code' => $badge['registration_code'],
                            'event' => $event->name,
                            'is_online_registration' => 1,
                            'position' => $position,
                            'event_id'=> $event->id,
                            'mobile' => ($badge['profile'])? $badge['profile']['phone'] : $badge['phone'],
                            'city' => $city,
                            'country' => $country,
                            'email' => ($badge['profile'])? $badge['profile']['user']['email'] : $badge['email']
                        ]);
                    }
            }
        }
        })->everyTwoMinutes();

//Exhibitor Badges
Schedule::call(function(){
    $events = Event::all();

    foreach ($events as $event) {
        $api_key = config('services.skylon.api_key');
        $response = Http::withoutVerifying()->withToken($api_key)->acceptJson()->get('https://www.myskylon.com/api/v1/exhibitors/'.$event->event_code);
        
        $exhibitors = $response->json();
        //loop through exhibitors
        foreach($exhibitors as $exhibitor){
            //check if exhibitor exists
            $exhibitor_exists = Exhibitor::where('code',$exhibitor['exhibitor_code'])->first();
            //if not, create exhibitor
            if(!$exhibitor_exists){
                Exhibitor::create([
                    'code' => $exhibitor['exhibitor_code'],
                    'company_name' => $exhibitor['company_name'],
                    'event_code' => $event->event_code
                ]);
                
            }else{
                $exhibitor_exists->update([
                    'company_name' => $exhibitor['company_name']
                ]);
            } 
        }
    }
})->everyTwoMinutes();

//Exhibitor Badges from skylon
Schedule::call(function(){
    //get events from current year
    $events = Event::whereYear('start_date', date('Y'))->get();
    $api_key = config('services.skylon.api_key');
    foreach ($events as $event) {
        foreach($event->exhibitors as $exhibitor){
            $response = Http::withoutVerifying()->withToken($api_key)->acceptJson()->get('https://www.myskylon.com/api/v1/exhibitor-badges/'.$exhibitor->code);
            
            $exhibitor_badges = $response->json();
            //loop through exhibitor badges
            foreach($exhibitor_badges as $exhibitor_badge){
            //check if exhibitor badge exists
            $exhibitor_badge_exists = ExhibitorBadge::where('name',$exhibitor_badge['name'])->where('exhibitor_id',$exhibitor_badge['exhibitor_id'])->first();
            //if not, create exhibitor badge
            if(!$exhibitor_badge_exists){
                ExhibitorBadge::create([
                    'name' => $exhibitor_badge['name'],
                    'exhibitor_id' => $exhibitor_badge['exhibitor_id'],
                    'badge_type_id' => $exhibitor_badge['badge_type_id'],
                ]);
            }
        }
    }
    }
});
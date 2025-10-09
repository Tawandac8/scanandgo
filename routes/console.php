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
        $events = Event::where('end_date','>=',Carbon::now()->format('Y-m-d'))->get();

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
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
use App\Models\SubEvent;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

//Visitors Badges
Schedule::call(function () {
        $events = Event::where('end_date','>=',Carbon::now()->format('Y-m-d'))->get();
        $badge_type = BadgeType::where('name','Visitor')->first();

        foreach ($events as $event) {
                $response = Http::withoutVerifying()->acceptJson()->get('https://www.zitfevents.com/api/v1/badges/'.$event->event_code);

                $badges = $response->json('data');

                if (!is_array($badges)) continue;

                foreach ($badges as $badge) {
                    try {
                        $badge_exists = Badge::where('reg_code', $badge['registration_code'])->first();

                        if ($badge['profile']) {
                            $company_name = isset($badge['profile']['company']) ? $badge['profile']['company']['name'] : ($badge['profile']['student']['school_name'] ?? '');
                            $position = isset($badge['profile']['company']) ? $badge['profile']['company']['department'] : ($badge['profile']['student']['level'] ?? '');
                            $city = isset($badge['profile']['city']) ? $badge['profile']['city']['name'] : '';
                            $country = isset($badge['profile']['country']) ? $badge['profile']['country']['name'] : '';
                        } else {
                            $company_name = $badge['company'] ?? '';
                            $position = $badge['position'] ?? '';
                            $city = $badge['city'] ?? '';
                            $country = $badge['country'] ?? '';
                        }

                        if (!$badge_exists) {
                            Badge::create([
                                'badge_type'             => $badge_type->name,
                                'badge_type_id'          => $badge_type->id,
                                'title'                  => ($badge['profile']) ? $badge['profile']['title'] : $badge['title'],
                                'first_name'             => ($badge['profile']) ? $badge['profile']['first_name'] : $badge['first_name'],
                                'last_name'              => ($badge['profile']) ? $badge['profile']['last_name'] : $badge['last_name'],
                                'company_name'           => $company_name,
                                'reg_code'               => $badge['registration_code'],
                                'event'                  => $event->name,
                                'is_online_registration' => 1,
                                'position'               => $position,
                                'event_id'               => $event->id,
                                'mobile'                 => ($badge['profile']) ? $badge['profile']['phone'] : $badge['phone'],
                                'city'                   => $city,
                                'country'                => $country,
                                'email'                  => ($badge['profile']) ? $badge['profile']['user']['email'] : $badge['email'],
                            ]);
                        }
                    } catch (Exception $e) {
                        Log::error('[Schedule:VisitorBadges] Badge error: '.$e->getMessage());
                    }
                }
        }
})->everyMinute();

//Exhibitor Badges
Schedule::call(function () {
    try {
        $events = Event::all();
        $api_key = config('services.skylon.api_key');

        foreach ($events as $event) {
            try {
                $response = Http::withoutVerifying()->withToken($api_key)->acceptJson()->get('https://www.myskylon.com/api/v1/exhibitors/'.$event->event_code);

                $exhibitors = $response->json();

                if (!is_array($exhibitors)) {
                    Log::warning('[Schedule:ExhibitorBadges] API returned non-array response for event: '.$event->event_code.'. Status: '.$response->status());
                    continue;
                }

                foreach ($exhibitors as $exhibitor) {
                    try {
                        $exhibitor_exists = Exhibitor::where('code', $exhibitor['exhibitor_code'])->first();

                        if (!$exhibitor_exists) {
                            Exhibitor::create([
                                'code'         => $exhibitor['exhibitor_code'],
                                'company_name' => $exhibitor['company_name'],
                                'event_code'   => $event->event_code,
                            ]);
                        } else {
                            $exhibitor_exists->update([
                                'company_name' => $exhibitor['company_name'],
                            ]);
                        }
                    } catch (Exception $e) {
                        Log::error('[Schedule:ExhibitorBadges] Exhibitor error: '.$e->getMessage());
                    }
                }
            } catch (Exception $e) {
                Log::error('[Schedule:ExhibitorBadges] Event loop error for '.$event->event_code.': '.$e->getMessage());
            }
        }
    } catch (Exception $e) {
        Log::error('[Schedule:ExhibitorBadges] Fatal error: '.$e->getMessage());
    }
})->everyMinute();

// //Exhibitor Badges from skylon
// Schedule::call(function(){
//     //get events from current year
//     $events = Event::whereYear('start_date', date('Y'))->get();
//     $api_key = config('services.skylon.api_key');
//     foreach ($events as $event) {
//         foreach($event->exhibitors as $exhibitor){
//             $response = Http::withoutVerifying()->withToken($api_key)->acceptJson()->get('https://www.myskylon.com/api/v1/exhibitor-badges/'.$exhibitor->code);
            
//             $exhibitor_badges = $response->json();
//             //loop through exhibitor badges
//             foreach($exhibitor_badges as $exhibitor_badge){
//                 //get badge type id
//                 $badge_type_id = BadgeType::where('name',$exhibitor_badge['badge_type']['name'])->first()->id;
//             //check if exhibitor badge exists
//             $exhibitor_badge_exists = ExhibitorBadge::where('batch_number',$exhibitor_badge['batch_number'])->where('exhibitor_id',$exhibitor_badge['exhibitor_id'])->first();
//             //if not, create exhibitor badge
//             if(!$exhibitor_badge_exists){
//                 ExhibitorBadge::create([
//                     'name' => $exhibitor_badge['name'],
//                     'exhibitor_id' => $exhibitor->id,
//                     'badge_type_id' => $badge_type_id,
//                     'batch_number' => $exhibitor_badge['batch_number'],
//                 ]);
//             }else{
//                 $exhibitor_badge_exists->name = $exhibitor_badge['name'];
//                 if($exhibitor_badge['is_printed'] == 1){
//                     $exhibitor_badge_exists->printed_copies = $exhibitor_badge['printed_quantity'];
//                     $exhibitor_badge_exists->is_printed = 1;
//                 }
//                 $exhibitor_badge_exists->save();
//             }
//         }
//     }
//     }
// })->everyMinute();

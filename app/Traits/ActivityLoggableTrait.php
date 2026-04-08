<?php

namespace App\Traits;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Facades\Request;
use Jenssegers\Agent\Facades\Agent;
use Torann\GeoIP\Facades\GeoIP;
use Auth;
use Spatie\Activitylog\Models\Activity;



trait ActivityLoggableTrait
{
				use LogsActivity;



				public function saveActivityLog($name,$description,$creatorId = null){
							$ipAddress = Request::ip() ;
							$browser = Agent::browser();
							$os = Agent::platform();

							$location = []; // Default value

                                try {
                                    $location = GeoIP::getLocation($ipAddress);
                                } catch (\Exception $e) {
                                    \Log::error('GeoIP error: ' . $e->getMessage());
                                }

							$user_id = \Auth::user()->id ?? "";
							$device = Agent::device() ?: 'Unknown';
							$locationName = !empty($location['city']) ? $location['city'] : 'Unknown';

							$creator = 1; // Default value
                            if (Auth::check()) { // Check if the user is authenticated
                                $user = Auth::user();
                                if ($user && method_exists($user, 'creatorId')) {
                                    $creator = $user->creatorId();
                                }
                            } else {
                                $creator = $creatorId ?? 1;
                            }


							Activity::create([
								'log_name' => $name,
								'description' =>$description,
								'properties' => [
									'created_by' => $creator,'user_id'=>$user_id,'browser' => $browser, 'os' => $os, 'location' => $locationName, 'device' => $device, 'ip_address'=>$ipAddress
								],
									]);
				}



				public function getActivitylogOptions(): LogOptions
							{
											$ipAddress = Request::ip();
											$browser = Agent::browser();
											$os = Agent::platform();
											$location = GeoIP::getLocation($ipAddress);
											$creator = \Auth::user()->creatorId();
											$device = Agent::device();

											return LogOptions::defaults()
															->logAll();
							}


}

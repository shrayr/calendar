<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    protected $fillable = [
        'name', 'access_token', 'calendar_id', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    /**
     * Get Google Client for show Calendar
     * @param $id
     * @return \Google_Client
     */
    public static function getClient($id){

        $calendar = Calendar::find($id);
        $client = new \Google_Client();
        $client->setAccessToken($calendar->access_token);

        if ($client->isAccessTokenExpired()) {
            $client->setScopes(implode(' ', array(\Google_Service_Calendar::CALENDAR_READONLY)));
            $client->setAuthConfig(config('google.config_dir'));
            $client->setApplicationName(config('google.calendar'));
            $client->setAccessType('offline');
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            $newAccessToken = json_encode($client->getAccessToken());
            $calendar->access_token = $newAccessToken;
            $calendar->save();
            }

        return $client;

    }


}

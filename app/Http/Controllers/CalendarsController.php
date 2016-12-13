<?php

namespace App\Http\Controllers;

use App\Calendar;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \GuzzleHttp\Client as GuzzleClient;
use Mockery\CountValidator\Exception;

class CalendarsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Index page of Calendars.. Show User All Calendars
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $calendars = Calendar::where('user_id', Auth::user()->id)->get();
        return view('calendars.index', compact('calendars'));
    }

    /**
     * Show Calendar
     *
     * @return \Illuminate\Http\Response
     */
    public function calendar($id, $calendar_id)
    {
        try {
            $client = Calendar::getClient($id);
        } catch (\Exception $e) {
            flash()->error($e->getMessage());
            return redirect()->route('calendars');
        }
        $service = new \Google_Service_Calendar($client);
        $calendarList = $service->calendarList->listCalendarList();

        // Get the next 100 events on the user's calendar.
        $calendarId = $calendar_id;
        $optParams = array(
            'maxResults' => 100,
            'orderBy' => 'startTime',
            'singleEvents' => TRUE,
            'timeMin' => date('c'),
        );
        try {
            $results = $service->events->listEvents($calendarId, $optParams);
        } catch (\Exception $e) {
           flash()->error($e->getMessage());
            return redirect()->route('calendars');
        }
        $events = [];
        if (count($results->getItems())) {
            foreach ($results->getItems() as $event) {
                $start = $event->start->dateTime;
                if (empty($start)) {
                    $start = $event->start->date;
                }
                $carbonDate = Carbon::parse($start);
                $dataString = $carbonDate->year . ',' . ($carbonDate->month - 1) . ',' . $carbonDate->day . ',' . $carbonDate->hour . ',' . $carbonDate->minute;

                $events[] = ['title' => $event->getSummary(),
                    'start' => $dataString,
                    'url' => $event->htmlLink,
                    'allDay' => false
                ];
            }
        }
        $events = json_encode($events);
        return view('calendars.show', compact('events', 'calendarList', 'id'));
    }


    /**
     * Add new Calendar...
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, ['name' => 'required']);
        Calendar::create([
            'user_id' => Auth::user()->id,
            'name' => $request['name'],
        ]);
        $client = new \Google_Client();
        $client->setApplicationName(config('google.calendar'));
        $client->setScopes(implode(' ', array(\Google_Service_Calendar::CALENDAR_READONLY)));
        $client->setAuthConfig(config('google.config_dir'));
        $client->setAccessType('offline');
        $client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/oauth2redirect');
        $authUrl = $client->createAuthUrl();
        return redirect($authUrl);
    }


    /**
     * Get Access Token
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function getAccessToken(Request $request)
    {
        $client = new \Google_Client();
        $client->setApplicationName(config('google.app_name'));
        $client->setScopes(implode(' ', array(\Google_Service_Calendar::CALENDAR_READONLY)));
        $client->setAuthConfig(config('google.config_dir'));
        $client->setAccessType('offline');
        if (isset($request['code'])) {
            $accessToken = $client->fetchAccessTokenWithAuthCode($request['code']);
            $accessToken = json_encode($accessToken);
            $calendar = Calendar::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->first();
            $calendar->access_token = $accessToken;
            $calendar->save();
            return redirect()->route('calendar', [$calendar->id, 'primary']);
        } else {
            return redirect()->route('calendars');
        }
    }


    /**
     * Update Access_Token (Ajax Call)
     * @param Request $request
     */
    public function updateAccessToken(Request $request)
    {
        $calendar = Calendar::find($request['id']);
        $token = trim($request['value']);
        $token = substr($token, 0, -1);
        $token = $token . ',"created":40740871568192}';   // test version
        $calendar->access_token = $token;
        $calendar->save();
        return 'Your Access Token is Updated';
    }


}

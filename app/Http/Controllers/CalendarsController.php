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
    public function index(){
        $calendars = Calendar::where('user_id', Auth::user()->id)->get();

        return view('calendars.index', compact('calendars'));
    }

    /**
     * Show Calendar
     *
     * @return \Illuminate\Http\Response
     */
    public function calendar($id)
    {
        $client = Calendar::getClient($id);
        $service = new \Google_Service_Calendar($client);

        // Get the next 100 events on the user's calendar.
        $calendarId = 'primary';
        $optParams = array(
            'maxResults' => 100,
            'orderBy' => 'startTime',
            'singleEvents' => TRUE,
            'timeMin' => date('c'),
        );
        try{
            $results = $service->events->listEvents($calendarId, $optParams);
        }catch(\Exception $e){
            flash()->success('Your Access Token is Expired');
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
        return view('calendars.show', compact('events'));
    }


    /**
     * Add new Calendar...
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request){
        $this->validate($request, ['name' => 'required', 'access_token' => 'required']);
        Calendar::create([
            'user_id' => Auth::user()->id,
            'name' => $request['name'],
            'access_token' => $request['access_token'],
            'calendar_id' => $request->get('calendar_id', 'primary'),
        ]);

        flash()->success('Calendar Added');
        return back();
    }


    /**
     * Get Access Token (For test)
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function getAccessToken(Request $request){

        $client = new \Google_Client();
        $client->setApplicationName(config('google.app_name'));
        $client->setScopes(implode(' ', array(\Google_Service_Calendar::CALENDAR_READONLY)));
        $client->setAuthConfig(config('google.config_dir'));
        $client->setAccessType('offline');
        $client->setRedirectUri(route('home') . '/get-access-token');

        if(isset($request['code'])){
            $accessToken = $client->fetchAccessTokenWithAuthCode($request['code']);
            dd($accessToken);
        }
         $authUrl = $client->createAuthUrl();
         return redirect($authUrl);

    }


}

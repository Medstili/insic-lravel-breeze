<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\User;

class CalendarController extends Controller
{
    

    /**
     * Display the calendar view.
     */
    public function index()
    {
        return view('calendar.index');
    }
    
    /**
     * Return appointments as JSON events.
     */
    public function getEvents(Request $request)
    {
        // Fetch all appointments (adjust query as needed)
        $appointments = Appointment::with('user')->get();
       
        $events = [];
        
        foreach ($appointments as $appointment) {
            $appointment_planning = json_decode($appointment->appointment_planning,true);
            foreach ($appointment_planning as $day => $time) {
                        $start_time = $time['startTime'];
                        $end_time = $time['endTime'];
            }
            $events[] = [
                'id'       => $appointment->id,
                // If using Scheduler, assign the resource (coach) id:
                'resourceId' => $appointment->coach_id, 
                'title' => $appointment->client_full_name, 
                'status' => $appointment->status,
                'start' => $appointment->$start_time,
                'end' => $appointment->$end_time,
            ];
        }
        return response()->json($events);
    }    

}

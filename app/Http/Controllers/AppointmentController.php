<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Patient;
use App\Models\User;
use App\Models\Speciality;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;



class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        
        $query = Appointment::query();

        // Filter by search query on patient or coach name
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->whereHas('patient', function($q1) use ($search) {
                    $q1->where('first_name', 'like', "%{$search}%");
                    $q1->orwhere('last_name', 'like', "%{$search}%");
                    $q1->orwhere('parent_last_name', 'like', "%{$search}%");
                    $q1->orwhere('parent_first_name', 'like', "%{$search}%");
                })
                ->orWhereHas('coach', function($q2) use ($search) {
                      $q2->where('full_name', 'like', "%{$search}%");
                  });
            });
        }
        // Filter by speciality
        if ($request->filled('speciality')) {
            $query->where('speciality_id', $request->speciality);
        }
        // filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        // filter by date 
        if ($request->filled('date')) {
            $search=$request->date;
            $query->whereRaw("JSON_EXTRACT(appointment_planning, '$.\"{$search}\"') IS NOT NULL");
        }
        $appointments = $query->with('patient','coach','Speciality')->get();
        $specilaities = Speciality::all();    
            return view('appointments/appointment', compact('appointments','specilaities'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $specialities = Speciality::all();
        return view('appointments/booking_appointment', compact('specialities'));
        

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        $request->validate([
            'patient_id'=>'required',
            'specialityId' => 'required',
            'planning' => 'required',
            'coach_id' => 'required',
        ]);
       

        $appointment = new Appointment();
        $appointment->patient_id = $request->patient_id;
        $appointment->coach_id = $request->coach_id;
        $appointment->appointment_planning= json_encode($request->planning);
        $appointment->speciality_id = $request->specialityId;
        $appointment->status = 'pending';
        $appointment->save();
        return response()->json([
            'success'     => true,
            'appointment' => $appointment,
        ], 200);
        // return redirect()->route('appointment.show')->with('success', 'Appointment created successfully');
        
        
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // -- dd($id); 
        $appointment = Appointment::with(['patient', 'coach','speciality'])->findOrFail($id);
        if( Auth::check()&& Auth::user()->role == 'admin'){
            return view('appointments/appointment_details', compact('appointment'));
        }else{
            return view('coach/coach_appointment_details', compact('appointment'));
        };

    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $appointment = Appointment:: findOrFail($id);
        if(AUth::check()&&Auth::user()->role=='admin'){
            return view('appointments/appointment_cancellation', compact('appointment'));
        } else {
            return view('coach/coach_appointment_cancellation', compact('appointment'));

        };  
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        
        $request->validate([
            'description'=> 'required',
            'cancelled_by'=>'required',
            'cancellation_type'=>'required'
        ]);
        $appointment = Appointment::find($id);
        $appointment->status = 'cancel';
        $appointment->reason = $request->description;
        $appointment->cancelledBy= $request->cancelled_by;
        $appointment->cancellation_type= $request->cancellation_type;

        $appointment->save();

        if (Auth::check() && Auth::user()->role == 'admin') {
            return redirect()->route('appointment.index')->with('success', 'Appointment cancelled successfully');
        }else{
            // return redirect()->route('appointment_list',compact(Auth::user()->id))->with('success', 'Appointment cancelled successfully');    
            return redirect()->back();
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $Appointment = Appointment::find($id);
        $this->deleteReport($id);
        $Appointment->delete();
        return redirect()->route('appointment.index');

    }
    // custom methods
    public function uploadReport(Request $request, $id)
    {
        $request->validate([
            'report' => 'required|file|mimes:pdf,doc,docx,txt|max:2048', // Max 2MB
        ]);

        $appointment = Appointment::findOrFail($id);

        // Store the file in the 'reports' folder under 'storage/app/public/reports/'
        $filename = time() . '_' . $request->file('report')->getClientOriginalName();
        $filePath = $request->file('report')->storeAs('reports', $filename, 'public');

        // Save the file path in the database
        $appointment->report_path = $filePath;
        $appointment->save();

        return redirect()->back();
    }
    public function downloadReport($id)
    {
        $appointment = Appointment::findOrFail($id);

        if (!$appointment->report_path) {
            return redirect()->back()->with('error', 'No report found.');
        }

        return response()->download(storage_path('app/public/' . $appointment->report_path));
    }
    public function deleteReport($id)
    {
        $appointment = Appointment::findOrFail($id);

        // Check if there is a report to delete
        if ($appointment->report_path) {
            // Delete the file from storage
            Storage::disk('public')->delete($appointment->report_path);

            // Remove the file path from the database
            $appointment->report_path = null;
            $appointment->save();

            return redirect()->back()->with('success', 'Report deleted successfully.');
        }

        return redirect()->back()->with('error', 'No report found for this appointment.');
    }
    public function updateAppointmentStatus($id, $status){
        $appointment = Appointment::findOrFail($id);
        $appointment->status = $status;
        $appointment->save();
        return redirect()->back();
    }
    public function viewReport($id)
    {
        $appointment = Appointment::findOrFail($id);

        if (!$appointment->report_path) {
            abort(404, 'Report not found.');
        }

        // Get the full path to the file stored on the 'public' disk
        $path = storage_path('app/public/' . $appointment->report_path);

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="report.pdf"'
        ]);
    }
    public function findAvailableCoach(Request $request)
    {
        // 1. Validate input: ensure we have a patient ID.
        $validated = $request->validate([
            'patient_id' => 'required|integer',
        ]);

        
        // 2. Retrieve the patient record.
        $patient = Patient::findOrFail($validated['patient_id']);
        // clean the patient priorities 
        $this->cleanExpiredPriorities($patient->id);

        // Assuming patient has a relation or attribute "speciality" (and its ID is used in coaches query)
        $speciality = $patient->speciality->id; 
    
        // 3. Decode the patient's priorities (available times, by level).
        $priorities = json_decode($patient->priorities, true);
        $outdatedPrioritiesMessage = '';
        if (count($priorities)==0) {
            $outdatedPrioritiesMessage ='priorities are outdated or there is no prioritites , please update the patient priorities';
        }
        
        // 4. Retrieve all available coaches for the given specialty.
        $coaches = User::where('speciality_id', $speciality)
                       ->where('is_available', true)
                       ->get();
    
        if ($coaches->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No available coaches found for this specialty.',
                'available_coaches' => [],
            ]);
        }
        
        // 5. Loop through priorities in order.
        foreach (['priority 1', 'priority 2', 'priority 3'] as $priorityKey) {
            if (isset($priorities[$priorityKey])) {
                // $priorityData is an associative array: date => array of intervals.
                $priorityData = $priorities[$priorityKey];
                foreach ($priorityData as $day => $intervals) {
                    // Check if the patient already has a pending appointment on that day.
                    // (We assume appointments with status 'pending' are blocking a new booking on the same day.)
                    $patientDailyAppt = Appointment::where('patient_id', $patient->id)
                        ->where('status', 'pending')
                        ->whereRaw("JSON_EXTRACT(appointment_planning, '$.\"$day\"') IS NOT NULL")
                        ->first();
                    if ($patientDailyAppt) {
                        // You could either skip this day or return a message.
                        return response()->json([
                            'success' => false,
                            'message' => "Patient already has a pending appointment on {$day}.",
                            'available_coaches' => []
                        ]);
                    }
                    
                    // For each available interval in the patient's priority.
                    foreach ($intervals as $interval) {
                        $pStartTime = $interval['startTime'];
                        $pEndTime   = $interval['endTime'];
    
                        $matchingCoaches = [];
                        // Check each coach for availability during this patient interval.
                        foreach ($coaches as $coach) {
                            $freeInterval = $this->isTimeAvailableForPatient($coach->id, $patient->id, $day, $pStartTime, $pEndTime);
                            if ($freeInterval !== null) {
                                $matchingCoaches[] = [
                                    'coach' => $coach,
                                    'patient' => $patient,
                                    'speciality' => $coach->speciality, 
                                    'date' => $day,
                                    'free_interval' => $freeInterval,
                                    'patient_requested_interval' => [
                                        'startTime' => $pStartTime,
                                        'endTime' => $pEndTime,
                                    ],
                                ];
                            }
                        }
    
                        if (!empty($matchingCoaches)) {
                            return response()->json([
                                'success' => true,
                                'priorityUsed' => $priorityKey,
                                'available_coaches' => $matchingCoaches,
                            ]);
                        }
                    }
                }
            }
        }
    
        // If no coaches are available for any of the patient’s priority intervals:
        return response()->json([
            'success' => false,
            'message' => 'No available coaches match the patient’s requirements.',
            'outdatedPrioritiesMessage'=>$outdatedPrioritiesMessage,
            'available_coaches' => []
        ]);
    }
    private function isTimeAvailableForPatient($coachId, $patientId, $day, $patientStartTime, $patientEndTime)
{
    // Convert the patient’s requested times (with the given day) to timestamps.
    $pStart = strtotime("$day $patientStartTime");
    $pEnd   = strtotime("$day $patientEndTime");


       //     // Check if the patient canceled the entire day.
        $fullDayCanceled = Appointment::where('patient_id', $patientId)       
        ->where('status', 'cancel')
        ->where('cancellation_type', 'entire day')
        ->get()
        ->filter(function ($appointment) use ($day) {
            $plan = json_decode($appointment->appointment_planning, true);
            return isset($plan[$day]);
        });
    
            log::debug('full cancellation', [$fullDayCanceled]);
            
        if ($fullDayCanceled->isNotEmpty()) {
            Log::debug('Patient canceled the entire day; blocking the full day.', ['day' => $day]);
            return null;
        }
    
    // 1. Check for conflicts with the patient’s own pending appointments.
    $existingPatientAppointments = Appointment::where('patient_id', $patientId)
        ->where('status', 'pending')
        ->whereRaw("JSON_EXTRACT(appointment_planning, '$.\"$day\"') IS NOT NULL")
        ->get();
    
    $patientOccupied = [];
    foreach ($existingPatientAppointments as $appointment) {
        $apptPlanning = json_decode($appointment->appointment_planning, true);
        if (isset($apptPlanning[$day])) {
            $aStart = strtotime("$day " . $apptPlanning[$day]['startTime']);
            $aEnd   = strtotime("$day " . $apptPlanning[$day]['endTime']);
            if ($aEnd > $pStart && $aStart < $pEnd) {
                $patientOccupied[] = [
                    'start' => max($pStart, $aStart),
                    'end'   => min($pEnd, $aEnd)
                ];
            }
        }
    }

    // 1b. Check for conflicts with the patient’s own canceled appointments.
    // We remove the whereRaw condition here so that canceled appointments are returned regardless.
    $canceledPatientAppointments = Appointment::where('patient_id', $patientId)
        ->where('status', 'cancel')
        ->get();
    
    foreach ($canceledPatientAppointments as $appointment) {
        $apptPlanning = json_decode($appointment->appointment_planning, true);
        // If there is planning data for the day, treat it as occupied.
        if (isset($apptPlanning[$day])) {
            $aStart = strtotime("$day " . $apptPlanning[$day]['startTime']);
            $aEnd   = strtotime("$day " . $apptPlanning[$day]['endTime']);
            if ($aEnd > $pStart && $aStart < $pEnd) {
                $patientOccupied[] = [
                    'start' => max($pStart, $aStart),
                    'end'   => min($pEnd, $aEnd)
                ];
            }
        }
    }
    
    // 2. Check the coach’s working schedule.
    $coach = User::find($coachId);
    if (!$coach) {
        return null;
    }
    $coachPlanning = json_decode($coach->planning, true);
    if (!isset($coachPlanning[$day])) {
        return null; // Coach is not working on that day.
    }
    $coachIntervals = $coachPlanning[$day]; // Array of working intervals on that day.
    $availableIntervals = [];
    // For each coach working interval, compute the overlapping segment with the patient’s requested window.
    foreach ($coachIntervals as $interval) {
        $coachStart = strtotime("$day " . $interval['startTime']);
        $coachEnd   = strtotime("$day " . $interval['endTime']);
        $overlapStart = max($pStart, $coachStart);
        $overlapEnd   = min($pEnd, $coachEnd);
        if ($overlapEnd > $overlapStart) {
            $availableIntervals[] = [
                'start' => $overlapStart,
                'end'   => $overlapEnd
            ];
        }
    }
    if (empty($availableIntervals)) {
        return null; // No overlap between coach working hours and patient requested time.
    }

    // 3. Check for conflicts with the coach’s pending appointments.
    $coachAppointments = Appointment::where('coach_id', $coachId)
        ->where('status', 'pending')
        ->whereRaw("JSON_EXTRACT(appointment_planning, '$.\"$day\"') IS NOT NULL")
        ->get();
    
    $coachOccupied = [];
    foreach ($coachAppointments as $appointment) {
        $apptPlanning = json_decode($appointment->appointment_planning, true);
        if (isset($apptPlanning[$day])) {
            $aStart = strtotime("$day " . $apptPlanning[$day]['startTime']);
            $aEnd   = strtotime("$day " . $apptPlanning[$day]['endTime']);
            if ($aEnd > $pStart && $aStart < $pEnd) {
                $coachOccupied[] = [
                    'start' => max($pStart, $aStart),
                    'end'   => min($pEnd, $aEnd)
                ];
            }
        }
    }
    
    // Combine all occupied intervals (from both patient and coach).
    $occupied = array_merge($patientOccupied, $coachOccupied);
    usort($occupied, function($a, $b) {
        return $a['start'] <=> $b['start'];
    });
    
    // 4. Subtract the occupied intervals from the available intervals (from coach planning overlap).
    $freeIntervals = [];
    foreach ($availableIntervals as $avail) {
        $currentStart = $avail['start'];
        foreach ($occupied as $block) {
            // If the occupied block is outside the available interval, skip.
            if ($block['end'] <= $avail['start'] || $block['start'] >= $avail['end']) {
                continue;
            }
            // If there is a gap before the block starts:
            if ($block['start'] > $currentStart) {
                $freeIntervals[] = [
                    'start' => $currentStart,
                    'end'   => $block['start']
                ];
            }
            $currentStart = max($currentStart, $block['end']);
        }
        if ($currentStart < $avail['end']) {
            $freeIntervals[] = [
                'start' => $currentStart,
                'end'   => $avail['end']
            ];
        }
    }
    
    // 5. Filter the free intervals to ensure they meet the minimum (45 min) and maximum (60 min) duration rules.
    $minDuration = 45 * 60; // 45 minutes in seconds.
    $maxDuration = 60 * 60; // 60 minutes in seconds.
    foreach ($freeIntervals as $free) {
        $duration = $free['end'] - $free['start'];
        if ($duration >= $minDuration) {
            // If the gap is larger than the maximum duration, cap it.
            $slotStart = $free['start'];
            $slotEnd = $free['end'];
            if ($duration > $maxDuration) {
                $slotEnd = $slotStart + $maxDuration;
            }
            return [
                'startTime' => date('H:i', $slotStart),
                'endTime'   => date('H:i', $slotEnd)
            ];
        }
    }
    
    // If no free slot meets the duration requirement, return null.
    return null;
    }
    private function cleanExpiredPriorities($patientId)
    {
        // Fetch the patient record
        // $patient = DB::table('patients')->where('id', $patientId)->first();
        $patient = Patient::findOrFail($patientId);
        
        if (!$patient || !$patient->priorities) {
            return false; // No priorities found
        }
        
        // Decode JSON priorities
        $priorities = json_decode($patient->priorities, true);
        $today = Carbon::today()->format('Y-m-d');
        
        // Iterate through each priority and remove past dates
        foreach ($priorities as $priorityKey => &$dates) {
            foreach (array_keys($dates) as $date) {
                if ($date < $today) {
                    unset($dates[$date]); // Remove expired date
                }
            }
            
            // Remove priority if all its dates are deleted
            if (empty($dates)) {
                unset($priorities[$priorityKey]);
            }
        }
        log::debug('updated priorities for patient ' .$patientId, $priorities);
        // Save the cleaned priorities back to the database
        $patient->priorities = json_encode($priorities); // Re-index array
        $patient->save();

        
        return true;
    }
    public function coach_appointments_list(Request $request,$id){
        
        $query = Appointment::where('coach_id', $id);

        // Filter by search query on patient or coach name
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->whereHas('patient', function($q1) use ($search) {
                    $q1->where('first_name', 'like', "%{$search}%");
                    $q1->orwhere('last_name', 'like', "%{$search}%");
                    $q1->orwhere('parent_last_name', 'like', "%{$search}%");
                    $q1->orwhere('parent_first_name', 'like', "%{$search}%");
                })
                ->orWhereHas('coach', function($q2) use ($search) {
                      $q2->where('full_name', 'like', "%{$search}%");
                  });
            });
        }
        // Filter by speciality
        if ($request->filled('speciality')) {
            $query->where('speciality_id', $request->speciality);
        }
        // filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        // filter by date 
        if ($request->filled('date')) {
            $search=$request->date;
            $query->whereRaw("JSON_EXTRACT(appointment_planning, '$.\"{$search}\"') IS NOT NULL");
        }
        $coachAppointments = $query->with('patient','coach','Speciality')->get();
        $specilaities = Speciality::all();    
        return view('coach/coach_appointments_list',compact('coachAppointments', 'specilaities'));
    }
    }

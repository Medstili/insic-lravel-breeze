<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Patient;
use App\Models\User;
use App\Models\Speciality;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Auth;
use \App\Models\BlockedSlot;
use App\Services\AppointmentScheduler;
use App\Models\SuggestedAppointment;


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
            'suggestedAppId'=>'required'
        ]);
       

        $appointment = new Appointment();
        $appointment->patient_id = $request->patient_id;
        $appointment->coach_id = $request->coach_id;
        $appointment->appointment_planning= json_encode($request->planning);
        $appointment->speciality_id = $request->specialityId;
        $appointment->status = 'pending';
        $appointment->save();

        $patient = Patient::findOrFail($request->patient_id);
        $coachAssignments = $patient->coaches; 

        //  reseting the usage of the coaches if all the coaches reached the max_appointments
        $allReached = true;
        foreach ($coachAssignments as $coach) {
            if ($coach->pivot->used_count < $coach->pivot->max_appointments) {
                $allReached = false;
                break;
            }
        }
        if ($allReached) {
            // Reset usage for all coaches.
            $resetData = [];
            foreach ($coachAssignments as $coach) {
                $resetData[$coach->id] = ['used_count' => 0];
            }
            $patient->coaches()->syncWithoutDetaching($resetData);
        }
        if ($coachAssignments->find($request->coach_id)->pivot->used_count >= $coachAssignments->find($request->coach_id)->pivot->max_appointments) {
            $patient->coaches()->updateExistingPivot($request->coach_id, ['used_count' => 0]);
        }
        $patient->coaches()->updateExistingPivot($request->coach_id, ['used_count' => DB::raw('used_count + 1')]);
        // updating the status of the suggested appointment
        $suggetsedApp = SuggestedAppointment::findOrFail($request->suggestedAppId);
        $suggetsedApp->status = 'booked';
        $suggetsedApp->appointment_id = $appointment->id;
        $suggetsedApp->save();


        return response()->json([
            'success'     => true,
            'appointment' => $appointment,
        ], 200);
        
        
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
            
        ]);

        $appointment = Appointment::find($id);
        $appointment->status = 'cancel';
        $appointment->reason = $request->description;
        $appointment->cancelledBy= $request->cancelled_by;

        
        
        $appointment->save();
        $patient = Patient::findOrFail($appointment->patient_id);
     
        $patient->coaches()->updateExistingPivot($request->coach_id, ['used_count' => DB::raw('used_count - 1')]);
        // updating the status of the suggested appointment
        $suggetsedApp = SuggestedAppointment::where('appointment_id', $id)->first();
        $suggetsedApp->status = 'suggested';
        $suggetsedApp->appointment_id = null;
        $suggetsedApp->save();

        if (Auth::check() && Auth::user()->role == 'admin') {
            return redirect()->route('appointment.index')->with('success', 'Appointment cancelled successfully');
        }else{
            return redirect()->back();
        }
    }

    public function update_app_patient(Request $request){


        $request->validate([
            'app_id' => 'required',
            'autoPatientId' => 'nullable',
            'manualPatientName' => 'nullable|string',
        ]);
        $patientId=0;
       
        if ($request->manualPatientName) {
            $existPatient = Patient::whereRaw("CONCAT(first_name, ' ', last_name) = ?", [$request->manualPatientName])
            ->orWhereRaw("CONCAT(parent_first_name, ' ', parent_last_name) = ?", [$request->manualPatientName])
            ->first();

            if (!$existPatient) {
                return response()->json([
                    'success' => false,
                    'exist'=>false,
                    'message' => 'Patient name does not exist.',
                ]);
            }else{
                $patientId = (int)$existPatient->id;
            }
        }else{
            $patientId = $request->autoPatientId;
        }

        $appointment = Appointment::findOrFail($request->app_id);
        $coach_id = $appointment->coach_id;
        $oldPatient=Patient::findOrFail($appointment->patient_id );
        if ($oldPatient->coaches()->find($coach_id)->pivot->used_count > 0) {
            $oldPatient->coaches()->updateExistingPivot($coach_id, ['used_count' => DB::raw('used_count - 1')]);
        }
        $appointment->patient_id = $patientId;
        $newPatient = Patient::findOrFail( $appointment->patient_id);
        // check if the coach exists in the patient's coaches
        $existingCoach = $newPatient->coaches->contains('id', $coach_id);
        if ($existingCoach) {
            Log::debug('existingCoach', [$existingCoach]);
            $newPatient = $appointment->patient;
            Log::debug('newPatient->coaches', [$newPatient->coaches]);
            $newPatient->coaches()->updateExistingPivot($coach_id, ['used_count' => DB::raw('used_count + 1')]);
        }
    
        $appointment->save();

        if (!$appointment) {
            return response()->json([
                'success' => false,
            ], 500);
        }
        return response()->json([
            'success' => true,
        ]);
    }

    public function update_app_planning(Request $request){
        $request->validate([
            'new_planning'=> 'required',
            'app_id'=>'required|integer'
        ]);

        $appointment = Appointment::findOrFail($request->app_id);
        $appointment->appointment_planning = json_decode($request->new_planning, true);
        $appointment->save();

        return redirect()->back()->with('success', 'Appointment planning updated successfully.');
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
                });

            });
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
    public function globalDashboard(Request $request){
        // Determine the current week start date from query parameters or default to this Monday.
        $currentWeekStart = $request->query('week') 
            ? Carbon::parse($request->query('week'))
            : Carbon::now()->startOfWeek(); // Assuming week starts on Monday

        // Create an array of dates for Monday through Saturday.
        $days = [];
        for ($i = 0; $i < 7; $i++) {
            $days[] = $currentWeekStart->copy()->addDays($i)->format('Y-m-d');
        }
        // dd($days);

        // Define 1-hour time slots from 12:00 to 20:00.
        $timeSlots = [];
        for ($hour = 12; $hour < 20; $hour++) {
            $timeSlots[] = [
                'start' => sprintf("%02d:00", $hour),
                'end'   => sprintf("%02d:00", $hour + 1),
            ];
        }

        // Retrieve all appointments (with related coach) from the database.
        $appointments = Appointment::with('coach','patient')->where('status','pending')->get();

        // Group appointments by date and coach.
        $organizedAppointments = [];
        foreach ($appointments as $appointment) {
            // Decode the appointment_planning JSON field.
            $planning = json_decode($appointment->appointment_planning, true);
            // dd($planning);
            if (is_array($planning)) {
                foreach ($planning as $date => $times) {

                        if (!empty($date)) {  
                        $coachId = $appointment->coach_id;
                        $patient_full_name = $appointment->patient->first_name != null ? 
                        ($appointment->patient->first_name." ".$appointment->patient->last_name)
                        : 
                        ( $appointment->patient->parent_first_name." ".$appointment->patient->parent_last_name) ;

                        // dd($appointment);

                        $organizedAppointments[$date][$coachId][] = [
                            'id'         => $appointment->id,
                            'status'=> $appointment->status,
                            'patient' => $patient_full_name,
                            'startTime'  => $times['startTime'], 
                            'endTime'    => $times['endTime'],  
                            'speciality_id' => $appointment->speciality_id,
                            'patient_id'=>$appointment->patient->id
                        ];
                    }
      
                }
            }
        }
        // Retrieve all coaches (adjust the query to your schema; here assuming a flag "is_coach")
        // $coaches = User::all();
        $coaches = User::select('id', 'full_name')->get();
        // Calculate previous and next week start dates for navigation.
        $prevWeekStart = $currentWeekStart->copy()->subWeek()->format('Y-m-d');
        $nextWeekStart = $currentWeekStart->copy()->addWeek()->format('Y-m-d');

        return view('admin/global_dashboard', compact(
            'days',
            'timeSlots',
            'coaches',
            'organizedAppointments',
            'currentWeekStart',
            'prevWeekStart',
            'nextWeekStart'
        ));
    }

    }

<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Services\AppointmentScheduler;
use \App\Models\SuggestedAppointment;
use \App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SuggestedAppointments extends Controller
{
  
    public function suggestedAppointments(Request $request)
    {
        // $currentWeekStart = $request->query('week') 
        // ? Carbon::parse($request->query('week'))
        // : Carbon::now()->startOfWeek(); 
       
        $currentWeekStart = Carbon::now()->startOfWeek(); // Monday
        $weekEnd = Carbon::now()->endOfWeek(); // Sunday


        

        $days = [];
        $messages = [];
        for ($i = 0; $i < 6; $i++) {
            $days[] = $currentWeekStart->copy()->addDays($i)->format('Y-m-d');
        }
        $timeSlots = [];
        for ($hour = 12; $hour < 20; $hour++) {
            $timeSlots[] = [
                'start' => sprintf("%02d:00", $hour),
                'end'   => sprintf("%02d:00", $hour + 1),
            ];
        }
        $weekEnd = $currentWeekStart->copy()->addDays(6)->format('Y-m-d');
        $schedule = new AppointmentScheduler();
        // $this->storeSuggestedAppointmentsForAllPatients($currentWeekStart->format('Y-m-d'), $weekEnd);
        $patientSchedules = SuggestedAppointment::whereBetween('Date', [$currentWeekStart->format('Y-m-d'), $weekEnd])->get();

        $patient_ids = $patientSchedules->pluck('patient_id')->unique();
        $patients = Patient::all();

        foreach ($patients as $patient) {
            $patient_full_name = in_array($patient->patient_type, ['kid', 'young']) ? $patient->first_name . ' ' . $patient->last_name : $patient->parent_first_name . ' ' . $patient->parent_last_name;

            if ($patient_ids->contains($patient->id)) {
                $patientAppointmentsCount = $patientSchedules->where('patient_id', $patient->id)->count();
                if ($patientAppointmentsCount < $patient->weekly_quota) {
                    $messages[] = 'Patient ' . $patient_full_name . ' hasn\'t reached his weekly quota. Please check ' . $patient_full_name .' priorities .';
                }
            }else{
                $messages[] = 'Patient ' . $patient_full_name . ' priorities doesnt match the coach availabilities . Please check ' . $patient_full_name .' priorities .';
            }
        }

        
        $allCoaches = User::select('id','full_name','speciality_id')->get();
        // $prevWeekStart = $currentWeekStart->copy()->subWeek()->format('Y-m-d');
        // $nextWeekStart = $currentWeekStart->copy()->addWeek()->format('Y-m-d');
        return view('admin/suggested_appointments', 
        compact(
        'days',
        'timeSlots',
                    'allCoaches', 
                    'patientSchedules', 
                    'currentWeekStart',
                    'messages'));
    }
    //  for all teh patients
    public function storeSuggestedAppointmentsForAllPatients(string $weekStart, string $weekEnd): array
    {
        $results = [];
        // Retrieve all patients (or apply filters as needed)
        $patients = Patient::all()->filter(function ($patient) {
            return $patient instanceof Patient;
        });

        // Instantiate your AppointmentScheduler (non-static method)
        $scheduler = new AppointmentScheduler();

        foreach ($patients as $patient) {
            // Ensure $patient is a single instance of App\Models\Patient
            if (!$patient instanceof Patient) {
                continue;
            }

            // This returns an array keyed by coach id with each containing appointments.
            $schedule = $scheduler->generateWeeklyAppointments($patient, $weekStart, $weekEnd);

            // Determine patient display name
            if ($patient->patient_type == 'kid' || $patient->patient_type == 'young') {
                $full_name = $patient->first_name . ' ' . $patient->last_name;
            } else {
                $full_name = $patient->parent_first_name . ' ' . $patient->parent_last_name;
            }

            // Loop over each coach schedule in the generated schedule.
            foreach ($schedule as $coachId => $coachSchedule) {
                // dd($coachId);
                if (!empty($coachSchedule['appointments'])) {
                    foreach ($coachSchedule['appointments'] as $appointmentSlot) {
                        // Use updateOrCreate so that if a suggestion for the same patient, coach, date, startTime, and endTime exists,
                        // it is updated instead of creating a duplicate.
                        SuggestedAppointment::updateOrCreate(
                            [
                                'coach_id'   => $coachId,
                                'patient_id' => $patient->id,
                                'startTime'  => $appointmentSlot['startTime'], 
                                'endTime'    => $appointmentSlot['endTime'],
                                'Date'       => $appointmentSlot['date'],
                            ],
                            [
                                'priority'   => $appointmentSlot['priority'],
                                'Status'     => 'suggested', 
                                'speciality_id'     => $coachSchedule['speciality_id'], 

                            ]
                        );
                    }
                }
            }
            
            // Append to results for debugging/confirmation if needed.
            $results[] = [
                'patient_id'    => $patient->id,
                'patient_name'  => $full_name,
                'speciality_id' => $patient->speciality_id,
                'schedule'      => $schedule,
            ];
        }
        
        return $results;
    }
    // for one patient
    public function storeSuggestedAppointmentsForOnePatient($patientId, string $weekStart, string $weekEnd): array{
        $results = [];
        $patient = Patient::where('id', $patientId)->first();

        // Instantiate your AppointmentScheduler (non-static method)
        $scheduler = new AppointmentScheduler();


            $schedule = $scheduler->generateWeeklyAppointments($patient, $weekStart, $weekEnd);

            if ($patient->patient_type == 'kid' || $patient->patient_type == 'young') {
                $full_name = $patient->first_name . ' ' . $patient->last_name;
            } else {
                $full_name = $patient->parent_first_name . ' ' . $patient->parent_last_name;
            }

            foreach ($schedule as $coachId => $coachSchedule) {
                // dd($coachId);
                if (!empty($coachSchedule['appointments'])) {
                    foreach ($coachSchedule['appointments'] as $appointmentSlot) {
                        // Use updateOrCreate so that if a suggestion for the same patient, coach, date, startTime, and endTime exists,
                        // it is updated instead of creating a duplicate.
                        SuggestedAppointment::updateOrCreate(
                            [
                                'coach_id'   => $coachId,
                                'patient_id' => $patient->id,
                                'startTime'  => $appointmentSlot['startTime'], 
                                'endTime'    => $appointmentSlot['endTime'],
                                'Date'       => $appointmentSlot['date'],
                            ],
                            [
                                'priority'   => $appointmentSlot['priority'],
                                'Status'     => 'suggested', 
                                'speciality_id'     => $coachSchedule['speciality_id'], 

                            ]
                        );
                    }
                }
            }
            
            // Append to results for debugging/confirmation if needed.
            $results[] = [
                'patient_id'    => $patient->id,
                'patient_name'  => $full_name,
                'speciality_id' => $patient->speciality_id,
                'schedule'      => $schedule,
            ];

        
        return $results;
    }
    public function update_Suggested_Appointment_Patient(Request $request){
        $request->validate([
            'suggestedAppId' => 'required',
            'autoPatientId' => 'nullable',
            'manualPatientName' => 'nullable|string',
        ]);

        $patientId=0;
        if ($request->manualPatientName) {
            $patient = Patient::whereRaw("CONCAT(first_name, ' ', last_name) = ?", [$request->manualPatientName])
            ->orWhereRaw("CONCAT(parent_first_name, ' ', parent_last_name) = ?", [$request->manualPatientName])
            ->first();

            if (!$patient) {
                return response()->json([
                    'success' => false,
                    'exist'=>false,
                    'message' => 'Patient name does not exist.',
                ]);
            }else{
                $patientId = (int)$patient->id;
            }
        }else{
            $patientId = $request->autoPatientId;
        }
 
        $suggestedApp = SuggestedAppointment::findOrFail($request->suggestedAppId);
        $suggestedApp->patient_id = $patientId;
        $suggestedApp->save();

        if (!$suggestedApp) {
            return response()->json([
                'success' => false,
                'exist'=>true
            ]);
        }
        return response()->json([
            'success' => true,
        ]);
    }
    public function update_Suggested_Appointment_Coach(Request $request){
        $request->validate([
            'suggestedAppId' => 'required',
            'coach_id' => 'required',
        ]);

        $suggestedApp = SuggestedAppointment::findOrFail($request->suggestedAppId);
        $suggestedApp->coach_id = $request->coach_id;
        $suggestedApp->save();
        if (!$suggestedApp) {
            return response()->json([
                'success' => false,
                'message' => 'Échec de la mise à jour du coach pour le rendez-vous suggéré.',
            ]);
        }
        return response()->json([
            'success' => true,
        ]);
    }

    public function update_sugg_app_planning(Request $request){
        $request->all();
        $request->validate([
            'app_id'=>'required|integer'
        ]);

        $appointment = SuggestedAppointment::findOrFail($request->app_id);
        $appointment->date = $request->date;
        $appointment->startTime = $request->start_time;
        $appointment->endTime = $request->end_time;

        
        $appointment->save();

        return redirect()->back()->with('success', 'Appointment planning updated successfully.');
    }

    public function blockSlot(Request $request) 
    {

        $request->validate([
            'id'       => 'required|integer',
        ]);


        SuggestedAppointment::where('id', $request->id)->delete();

        return response()->json(['success' => true, 'message' => 'Slot blocked successfully.']);
    }
    public function deleteSuggestedAppointment( $id)
    {


        $appointment = SuggestedAppointment::findOrFail($id);
        $appointment->delete();

        return  redirect()->back()->with('success', 'Appointment deleted successfully.');
    }

}

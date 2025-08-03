<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \App\Models\Speciality;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;


class PatientController extends Controller
{
   /**
     * Display a listing of the resource.
     */
    
     public function index(Request $request)
    {
  

            $query = Patient::query();

            // Filter by search query on patient or coach name
            if ($request->filled('q')) {
                $search = $request->q;
                $query->where(function($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('parent_first_name', 'like', "%{$search}%")
                    ->orWhere('parent_last_name', 'like', "%{$search}%");
                    });
            }

            //  filter by type 
            if ($request->filled('patient_type')) {
                $query->where('patient_type', $request->patient_type);
            }
        
            // Filter by speciality
            if ($request->filled('gender')) {
                $query->where('gender', $request->gender);
            }
        
            if ($request->filled('specialities')) {
                $specialityId = $request->specialities; 
                $query->where('speciality_id', $specialityId);
            }
            // Optionally, order by date or other column
            $patients = $query->paginate(15);
            // $patients = $query->get();
            $specialities = Speciality::all();
            $patientCount = Patient::count();
            $adultCount = Patient::where('patient_type', 'adult')->count();
            $kidCount = Patient::where('patient_type', 'kid')->count();
            $youngCount = Patient::where('patient_type', 'young')->count();
            return view('patient/patients', compact('patients', 'specialities','adultCount', 'patientCount','kidCount', 'youngCount')) ;
    }

    /**
     * Show the form for creating a new resource.
     */
    
     public function create()
    {
        $specialities= Speciality::all();
        $coaches = User::select('id', 'full_name',)->with('speciality')->get();

        return view('patient.add_patient',compact('specialities','coaches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)   
    {
        if(is_null($request->coaches)){
            return redirect()->back()->withErrors(['no_coaches' => 'Aucun coach n\'a été sélectionné.']);
        }

        request()->validate([
            'patient_type'        => 'required',
            'PatientGender'       => 'required',
            'age'                 => 'required',
            'kid_last_name'       => 'nullable',
            'kid_first_name'      => 'nullable',
            'kid_ecole'           => 'nullable',
            'kid_system'          => 'nullable',
            'parent_first_name'   => 'nullable',
            'parent_last_name'    => 'required',
            'parent_profession'   => 'required',
            'parent_phone'        => 'required',
            'parent_etablissement'=> 'required',
            'parent_email'        => 'required|email|unique:patients,email',
            'parent_adresse'      => 'required',
            'mode'                => 'required',
            'abonnement'          => 'required',
            'speciality_id'       => 'required',
            'max_appointments'    => 'required',
            'priorities'          => 'required',
            'image'               => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ],[
            "parent_email.unique" => "cet email est déjà utilisé"
        ]);

        $existingPatient = null;

        if ($request->patient_type !== 'adult') {
            // Check if kid's first and last name exist by comparing with parent's first and last name
            $existingPatient = Patient::where('first_name', $request->kid_first_name)
                ->where('last_name', $request->kid_last_name)
                ->orWhere(function ($query) use ($request) {
                    $query->where('parent_first_name', $request->kid_first_name)
                          ->where('parent_last_name', $request->kid_last_name);
                })
                ->first();
        } else {
            // Check if parent's first and last name exist by comparing with kid's first and last name
            $existingPatient = Patient::where('parent_first_name', $request->parent_first_name)
                ->where('parent_last_name', $request->parent_last_name)
                ->orWhere(function ($query) use ($request) {
                    $query->where('first_name', $request->parent_first_name)
                          ->where('last_name', $request->parent_last_name);
                })
                ->first();
        }

        Log::debug('existingPatient is null?', ['is_null' => is_null($existingPatient)]);
        Log::debug('existingPatient', [$existingPatient]);

        if (!is_null($existingPatient)) {
            return redirect()->back()->withErrors(['patient_exists' => 'Ce patient existe déjà.']);
        }
         
        if($request->priorities === "{}"){
            return redirect()->back()->withErrors(['priorities' => 'priorities is required']);
        }

        $patient = new Patient();
        $patient->patient_type = $request->patient_type;
        $patient->gender = $request->PatientGender;
        $patient->age = $request->age;
        $patient->last_name = $request->kid_last_name;
        $patient->first_name = $request->kid_first_name;
        $patient->ecole = $request->kid_ecole;
        $patient->system = $request->kid_system;
        $patient->parent_first_name = $request->parent_first_name;
        $patient->parent_last_name = $request->parent_last_name;
        $patient->profession = $request->parent_profession;
        $patient->phone = $request->parent_phone;
        $patient->etablissment = $request->parent_etablissement;
        $patient->email = $request->parent_email;
        $patient->address = $request->parent_adresse;
        $patient->mode = $request->mode;
        $patient->subscription = $request->abonnement;
        $patient->speciality_id = $request->speciality_id;
        $patient->priorities = $request->priorities;
        $patient->weekly_quota = (int) $request->max_appointments;

        if ($request->hasFile('image')) {
            if ($patient->image) {
                Storage::delete('public/' . $patient->image);
            }
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('patientImages', $imageName, 'public');
                      
            $patient->image_path = $imagePath;
        };
      
        $patient->save();

        $coachAssignments = [];
            foreach ($request->coaches as $index => $value) {
                $coachAssignments[] = [$value, $request['coach' . $value]];
            }
            $coachOrder = json_decode($request->coach_order, true); 

        
            foreach ($coachOrder as $position => $coachId) {
    
                $capacity = null;
                foreach ($coachAssignments as $assignment) {
                    if ($assignment[0] == $coachId) {
                        $capacity = $assignment[1];
                        break;
                    }
                }
    
                if ($capacity !== null) {
                    $patient->coaches()->attach($coachId, [
                        'max_appointments' => $capacity,
                        'position' => $position+1  
                    ]);
                };
            };


      



        $SuggestedAppointments = new SuggestedAppointments();
        $currentWeekStart = Carbon::now()->startOfWeek(); // Monday
        $weekEnd = Carbon::now()->endOfWeek(); // Sunday
        $SuggestedAppointments->storeSuggestedAppointmentsForOnePatient($patient->id,$currentWeekStart->format('Y-m-d'), $weekEnd);
        
        return redirect()->route('patient.index')->with('success', 'patient added successfully');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
        $patient = Patient::with('speciality')->findOrFail($id);
        $specialities= Speciality::all();
        $appointments = $patient->appointments()->with('coach')->orderBy('created_at', 'asc')->get();
        // dd($appointments);
        if (Auth::check() && Auth::user()->role == 'admin') {
            # code...
            return view('patient/patient_profile', compact('patient', 'specialities', 'appointments'));	
        }else {
            return view('caoch/coach_patient_profile', compact('patient','specialities'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
        
    public function edit(string $id)
    {
        $patient = Patient::with('coaches')->findOrFail($id);
        $specialities = Speciality::all();

        // Get all coaches from the database.
        $allCoaches = User::select('id', 'full_name')->get();

        // Retrieve the assigned coaches from the patient (this collection is already ordered by pivot->position because of your relationship)
        $assignedCoaches = $patient->coaches;
        
        // Determine unassigned coaches
        $unassignedCoaches = $allCoaches->diff($assignedCoaches);
        
        // Merge assigned coaches (in order) first, then unassigned coaches.
        $orderedCoaches = $assignedCoaches->merge($unassignedCoaches);

        return view('patient.edit_patient', compact('patient', 'specialities', 'orderedCoaches'));
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id){
        // Retrieve the selected coach IDs from the form
        $newCoachIds = $request->coaches; 
        if (empty($newCoachIds)) {
            return redirect()->back()->withErrors(['coaches' => 'Au moins un coach est requis']);
        }

                $existingPatient = null;

                if ($request->patient_type !== 'adult') {
                    // Check if kid's first and last name exist by comparing with parent's first and last name
                    $existingPatient = Patient::where('first_name', $request->kid_first_name)
                    ->where('last_name', $request->kid_last_name)
                    ->orWhere(function ($query) use ($request) {
                        $query->where('parent_first_name', $request->kid_first_name)
                          ->where('parent_last_name', $request->kid_last_name);
                    })
                    ->where('id', '!=', $id) // Ignore the current patient
                    ->first();
                } else {
                    // Check if parent's first and last name exist by comparing with kid's first and last name
                    $existingPatient = Patient::where('parent_first_name', $request->parent_first_name)
                    ->where('parent_last_name', $request->parent_last_name)
                    ->orWhere(function ($query) use ($request) {
                        $query->where('first_name', $request->parent_first_name)
                          ->where('last_name', $request->parent_last_name);
                    })
                    ->where('id', '!=', $id) // Ignore the current patient
                    ->first();
                }

        if (!is_null($existingPatient) && $existingPatient->id != $id) {
            return redirect()->back()->withErrors(['patient_exists' => 'Ce patient existe déjà.']);
        }
        // Prepare pivot data for each new coach (capacity) first
        $syncData = [];
        foreach ($newCoachIds as $coachId) {
            $syncData[$coachId] = ['max_appointments' => $request->input('coach' . $coachId)];
        }
        
        // Validate request fields

        $request->validate([
            'patient_type'        => 'required',
            'PatientGender'       => 'required',
            'age'                 => 'required',
            'kid_last_name'       => 'nullable',
            'kid_first_name'      => 'nullable',
            'kid_ecole'           => 'nullable',
            'kid_system'          => 'nullable',
            'parent_first_name'   => 'required',
            'parent_last_name'    => 'required',
            'parent_profession'   => 'required',
            'parent_phone'        => 'required',
            'parent_etablissement'=> 'required',
            'parent_email'        => ['required', 'email', Rule::unique('patients', 'email')->ignore($id)],
            'parent_adresse'      => 'required',
            'mode'                => 'required',
            'abonnement'          => 'required',
            'speciality_id'       => 'required',
            'max_appointments'    => 'required',
            'priorities'          => 'required',
            'image'               => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'parent_email.unique' => 'this email already in use'
        ]);
        
        if ($request->priorities === "{}") {
            return redirect()->back()->withErrors(['priorities' => 'priorities is required']);
        }
        
        // Retrieve and update the patient record
        $patient = Patient::findOrFail($id);
        $patient->patient_type = $request->patient_type;
        $patient->gender = $request->PatientGender;
        $patient->age = $request->age;
        $patient->last_name = $request->kid_last_name;
        $patient->first_name = $request->kid_first_name;
        $patient->ecole = $request->kid_ecole;
        $patient->system = $request->kid_system;
        $patient->parent_first_name = $request->parent_first_name;
        $patient->parent_last_name = $request->parent_last_name;
        $patient->profession = $request->parent_profession;
        $patient->phone = $request->parent_phone;
        $patient->etablissment = $request->parent_etablissement;
        $patient->email = $request->parent_email;
        $patient->address = $request->parent_adresse;
        $patient->mode = $request->mode;
        $patient->subscription = $request->abonnement;
        $patient->speciality_id = $request->speciality_id;
        $patient->priorities = $request->priorities;
        $patient->weekly_quota = (int)$request->max_appointments;
        
        // Handle image upload if provided
        if ($request->hasFile('image')) {
            if ($patient->image_path) {
                // Storage::delete('public/' . $patient->image_path);
                Storage::disk('public')->delete($patient->image_path);
            }
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('patientImages', $imageName, 'public');
            $patient->image_path = $imagePath;
        }
        
        $patient->save();
        
        // **NEW CODE:** If a coach order is provided, add the order to the pivot data.
        if ($request->filled('coach_order')) {
            $order = json_decode($request->coach_order, true); // e.g. [4, 10, 2, ...]
            foreach ($order as $position => $coachId) {
                // Only add order for coaches that are selected in the form.
                if (in_array($coachId, $newCoachIds)) {
                    $syncData[$coachId]['position'] = $position+1; // positions are 0-based
                }
            }
        }
        
        // Sync the pivot data with the patient record.
        $patient->coaches()->sync($syncData);
        
        // Store suggested appointments for this patient (if needed)
        $SuggestedAppointments = new SuggestedAppointments();
        $currentWeekStart = Carbon::now()->startOfWeek(); // Monday
        $weekEnd = Carbon::now()->endOfWeek(); // Sunday
        $SuggestedAppointments->storeSuggestedAppointmentsForOnePatient($patient->id, $currentWeekStart->format('Y-m-d'), $weekEnd);
        
        return redirect()->route('patient.show', $id)->with('updated', 'updated successfully');
    }


    public function destroy(string $id)
    {
        $patient = Patient::findOrFail($id);
        $patient->coaches->each(function ($coach) use ($id) {
            $canSee = (array) $coach->can_see;

            if (($key = array_search($id, $canSee)) !== false) {
            unset($canSee[$key]);
            $coach->can_see = array_values($canSee); // Reindex the array
            $coach->save();
            }
        });

        $patient->delete();
        return redirect()->route('patient.index');

    }

     // coach functions 
    public function coach_patient_profile($id){
        $patient = Patient::with('coaches')->findOrFail($id);  
        return view('coach/coach_patient_profile', compact('patient')) ;     
    }

    public function coach_patinets_list(Request $request,$coachId){

        $query = Patient::whereHas('appointments', function($q) use ($coachId) {
            $q->where('coach_id', $coachId);
        });
    
        // Filter by search query on patient or coach name
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('parent_first_name', 'like', "%{$search}%")
                ->orWhere('parent_last_name', 'like', "%{$search}%");
                });
        }
        //  filter by type 
        if ($request->filled('patient_type')) {
            $query->where('patient_type', $request->patient_type);
        }
        // Filter by speciality
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }
        $patients = $query->get();
        $specialities = Speciality::all();
        return view('coach/coach_patients_list', compact('patients', 'specialities')) ;
    }

    public function availablePatients(Request $request)
    {
        $request->validate([
            'speciality_id' => 'required|integer',
            'date' => 'required|date',
            'startTime' => 'required',
            'endTime' => 'required',
            'patient_id'=>'required'
        ]);
        $sugg_Speciality_id = $request->speciality_id;
        $sugg_date = $request->date;
        $sugg_startTime = $request->startTime;
        $sugg_endTime = $request->endTime;

        $patients = Patient::where('speciality_id', $sugg_Speciality_id)
            ->whereNot('id', $request->patient_id)
            ->get();

        $available_patients = [];

        $sugg_start = strtotime($sugg_startTime);
        $sugg_end = strtotime($sugg_endTime);
        // Log::debug('sugg_start',[$sugg_start]);
        // Log::debug('sugg_end',[$sugg_end]);

        foreach ($patients as $patient) {
            // all patient 

            $full_name = in_array($patient->patient_type, ['kid', 'young']) ?
            $patient->first_name . ' ' . $patient->last_name :
            $patient->parent_first_name . ' ' . $patient->parent_last_name;

            // available patient 
            $priorities = json_decode($patient->priorities, true);

            foreach($priorities as $priorityLevel => $priorityData){

                foreach ($priorityData as $day => $intervals) {
                    $transformedSuggDay =  date('l',strtotime($sugg_date));
                    $transformedPriorityDay =  date('l',strtotime($day));
  
                
                    if ($transformedSuggDay == $transformedPriorityDay) {
                        foreach ($intervals as $interval) {
                            $startTime = strtotime($interval['startTime']);
                            $endTime = strtotime($interval['endTime']);
        
                            if ($startTime <=  $sugg_start && $endTime >= $sugg_end) {
                                $patientApp = $patient->appointments()
                                ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(appointment_planning, '$.\"$sugg_date\".startTime')) = ?", [$sugg_startTime])
                                ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(appointment_planning, '$.\"$sugg_date\".endTime')) = ?", [$sugg_endTime])
                                ->exists();
                            
                            
                                if (!$patientApp) {
                                    $patient_full_name = in_array($patient->patient_type, ['kid', 'young']) ?
                                        $patient->first_name . ' ' . $patient->last_name :
                                        $patient->parent_first_name . ' ' . $patient->parent_last_name;
        
                                    $available_patients[] = [
                                        'id' => $patient->id,
                                        'full_name' => $patient_full_name
                                    ];
                                    break 2;
                                }
                            }
                        }
                        Log::debug('available_patients',[$available_patients]);
                    }
                }
            }

     
        }
        

        if (empty($available_patients)) {
            return response()->json(
                ['success' => false,
                        'msg'=>'No available patients found',
                        // 'all_patients'=>$all_patient,
                        $available_patients

                    ]);
        }else{
            return response()->json(
                ['success' => true,
                        'msg'=>'available patients found',
                        // 'all_patients'=>$all_patient,
                        'available_patients'=>$available_patients
                    ]);
        }

    }

    public function who_can_See(Request $request, string $id)
    {
        // Validate input: ensure the 'coaches' field is present and is an array.
        $request->validate([
            'coaches' => 'nullable|array',
        ]);
    
        $selectedCoachIds = $request->coaches ?? []; 

        $coaches = User::all();
    
        foreach ($coaches as $coach) {
            $canSee = (array) $coach->can_see;
    
            if (in_array($coach->id, $selectedCoachIds)) {

                if (!in_array($id, $canSee)) {
                    $canSee[] = $id;
                }
            } else { 
                if (($key = array_search($id, $canSee)) !== false) {
                    unset($canSee[$key]);
                }
            }
            // Reindex the array and save.
            $coach->can_see = array_values($canSee);
            $coach->save();
        }
    
        return redirect()->back()->with('success', 'Access permissions updated successfully.');
    }
    
    


}

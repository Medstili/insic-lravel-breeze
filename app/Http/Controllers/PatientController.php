<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \App\Models\Speciality;
use App\Models\User;

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
        
            // Optionally, order by date or other column
            $patients = $query->get();
            return view('patient/patients', compact('patients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $specialities= \App\Models\Speciality::all();
        return view('patient\add_patient',compact('specialities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // dd( $request->all());
        request()->validate([
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
            'parent_email'        => 'required',
            'parent_adresse'      => 'required',
            'mode'                => 'required',
            'abonnement'          => 'required',
            'specialty_id'        => 'required',
            'priorities'          => 'required',
        ]);
        

        
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
        $patient->speciality_id = $request->specialty_id;
        $patient->priorities = $request->priorities;
    

        $patient->save();
        return redirect()->route('patient.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
        $patient = Patient::with('speciality')->findOrFail($id);
        $specialities= Speciality::all();
        if (Auth::check() && Auth::user()->role == 'admin') {
            # code...
            return view('patient/patient_profile', compact('patient', 'specialities'));
        }else {
            return view('caoch/coach_patient_profile', compact('patient','specialities'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $patient = Patient::findOrFail($id);
        $specialities= \App\Models\Speciality::all();
        return view('patient/edit_patient', compact('patient','specialities'));
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request->all());
        
        request()->validate([
            'patient_type'        => 'required',
            'PatientGender'       => 'required',
            'age'                 => 'required',
            'kid_last_name'       => 'nullable',// nullable
            'kid_first_name'      => 'nullable',// nullable
            'kid_ecole'           => 'nullable',// nullable
            'kid_system'          => 'nullable',// nullable
            'parent_first_name'   => 'required',
            'parent_last_name'    => 'required',
            'parent_profession'   => 'required',
            'parent_phone'        => 'required',
            'parent_etablissement'=> 'required',
            'parent_email'        => 'required',
            'parent_adresse'      => 'required',
            'mode'                => 'required',
            'abonnement'          => 'required',
            'specialty_id'        => 'required',
            'priorities'          => 'required',
        ]);
        

        
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
        $patient->speciality_id = $request->specialty_id;
        $patient->priorities = $request->priorities;
    

        $patient->save();
        return redirect()->route('patient.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();
        return redirect()->route('patient.index');

    }

     // coach functions 
    public function coach_patient_profile($id){
        $patient = Patient::with('coach')->findOrFail($id);  
        return view('coach_patient_profile', compact('patient')) ;     
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


}

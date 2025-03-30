<?php

namespace App\Http\Controllers;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Speciality;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Termwind\Components\BreakLine;

class UserController extends Controller
{

    public function index(Request $request)
    {
        
        $specialities = Speciality::all();
        
        $query = User::query();

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%");
            });
        }
    
        if ($request->filled('availability')) {
            $query->where('is_available', $request->availability);
        }
        if ($request->filled('specialities')) {
            $specialityId = $request->specialities; 
            $query->where('speciality_id', $specialityId);
        }
        
        
    
        $users = $query->with('speciality')->get()->except(Auth::id());
    
        return view('user/coaches', compact('users', 'specialities'));
    }
    public function create()
    {
        
        $specialities = Speciality::all();
        return view('user/add_coach', compact('specialities'));

    }
    public function store(Request $request) {
        // dd($request->all());
            $request->validate([
                'full_name' => 'required|unique:users,full_name',
                'email' => 'required|email|unique:users,email',
                'password' => [
                    'required',
                    Password::min(8)
                        ->mixedCase()   
                        ->letters()      
                        ->numbers()          
                        ->uncompromised(), 
                ],
                'tel' => 'required',
                'speciality_id' => 'required',
                'is_available' => 'required',
                "planning"=>'required',
                "role"=>"required",
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'

            ],[
                'email.unique' => 'This email is already in use.'
            ]);

            if ($request->planning === '{}' ) {
                return redirect()->back()->withErrors(['planning' => 'planning is required']);
            }

      
        
            $user = new User();
            $user->full_name = $request->full_name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->phone = $request->tel;
            $user->speciality_id = $request->speciality_id;
            $user->is_available = $request->is_available;
            $user->planning = $request->planning;
            $user->role = $request->role;
 

            if ($request->hasFile('image')) {
                if ($user->image) {
                    Storage::delete('public/' . $user->image);
                }
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $imagePath = $image->storeAs('uploadsImages', $imageName, 'public');
                $user->image_path = $imagePath;
            };
            $user->save();
            // dd($user);
            return redirect()->route('user.index')->with('success', 'Coach added successfully!');
    }
    public function show(string $id)
    {


        
        $coach = User::with('speciality')->findOrFail($id);
        $specialities = Speciality::all();
        $coachAppointments = Appointment::with('patient')->where('coach_id', $id)->get();
        $reports = [];

        // dd($coach->can_see);
        if ($coach->can_see === null) {
            return view('user/coach_profile', compact('coach','specialities','coachAppointments','reports'));
        }else{
            foreach ($coach->can_see as $p_id) {
                $p_app = Appointment::where('patient_id', $p_id)->get();
                $patient = Patient::find($p_id);
    
                $full_name = in_array($patient->patient_type, ['kid', 'young']) 
                    ? "{$patient->first_name} {$patient->last_name}" 
                    : "{$patient->parent_first_name} {$patient->parent_last_name}";
    
                foreach ($p_app as $appointment) {
                if (!isset($reports[$p_id])) {
                    $reports[$p_id] = [
                    'patient_name' => $full_name,
                    'report' => [],
                    ];
                }
                $reports[$p_id]['report'][]= ['content' => $appointment->report_path, 'app_id' => $appointment->id];
                }
            }
            return view('user/coach_profile', compact('coach','specialities','coachAppointments','reports'));
    
        }
    
    }

    public function edit(string $id)
    {
        
        $user = User::find($id);
        // $specialities = User::select('speciality')->distinct()->pluck('speciality'); 
        $specialities = Speciality::all();
        return view('user/edit_coach', compact('user','specialities'));

    }
    public function update(Request $request, string $id)
    {
        // dd($request->all());
        $user = User::findOrFail($id);

        $request->validate([
            'full_name' => 'required|string|max:255|unique:users,full_name,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'tel' => 'required',
            'speciality_id' => 'required',
            'is_available'=> 'required',
            'planning'=>'required',
            'role'=>'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ],[
            'email.unique' => 'This email is already in use.',
            'full_name.unique' => 'This full name is already in use.'
        ]);

          // Log validation errors for debugging
    if ($errors = session('errors')) {
        Log::debug('Validation Errors:', $errors->toArray());
    }
        if ($request->planning === '{}' ) {
            return redirect()->back()->withErrors(['planning' => 'planning is required']);
        }

        // $user = User::find($id);
        $user->full_name = $request->full_name;
        $user->email = $request->email;
        $user->is_available = (int)$request->is_available;
        $user->speciality_id = $request->speciality_id;
        $user->phone = $request->tel;
        $user->role = $request->role;
        $user->planning = $request->planning;
         

        if ($request->hasFile('image')) {
            // dd('here');
            if ($user->image_path) {

                Storage::disk('public')->delete($user->image_path);

            }
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('uploadsImages', $imageName, 'public');
            $user->image_path = $imagePath;
        };
     
        $user->save();
       return redirect()->route('user.show', $user->id)->with('success','updated successfully');
    }
    public function destroy(string $id)
    {
        
        $user = User::find($id);
        $user->delete();
        return redirect()->route('user.index');
    }

    // coach functions 
    public function coach_edit(string $id)
    {  
        $user = User::find($id);
        // $specialities = User::select('speciality')->distinct()->pluck('speciality'); 
        $specialities = Speciality::all();
        return view('coach/edit_profile', compact('user','specialities'));
    }
    
    public function coach_update(Request $request, string $id)
    {
        // dd($request->all());
        $request->validate([
            'full_name' => 'required',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($id)],
            'tel' => 'required',
            'speciality_id' => 'required',
            'is_available'=> 'required',
            'role'=>'required',
            'planning'=>'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'


        ],[
            'email.unique' => 'This email is already in use.'
        ]);

        if ($request->planning === '{}' ) {
            return redirect()->back()->withErrors(['planning' => 'planning is required']);
        };

        $user = User::find($id);
        $user->full_name = $request->full_name;
        $user->email = $request->email;
        $user->is_available = (int)$request->is_available;
        $user->speciality_id = $request->speciality_id;
        $user->phone = $request->tel;
        $user->role = $request->role;
        $user->planning = $request->planning;

        if ($request->hasFile('image')) {
            if ($user->image_path) {
                Storage::disk('public')->delete($user->image_path);
            }
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('uploadsImages', $imageName, 'public');
            $user->image_path = $imagePath;
        };
     
        $user->save();
       return redirect()->route('coach_profile', $user->id)->with('success','updated successfully');
    }
    public function showCoachProfile()
    {
        $coach = Auth::user();
        $id = $coach->id;
        $specialities = Speciality::all();
        $coachAppointments = Appointment::with('patient')->where('coach_id', $id)->get();
        $reports = [];
      // dd($coach->can_see);
        if ($coach->can_see === null) {
            return view('coach/profile', compact('coach','specialities','coachAppointments','reports'));
        }else{
            foreach ($coach->can_see as $p_id) {
                $p_app = Appointment::where('patient_id', $p_id)->get();
                $patient = Patient::find($p_id);

                $full_name = in_array($patient->patient_type, ['kid', 'young']) 
                    ? "{$patient->first_name} {$patient->last_name}" 
                    : "{$patient->parent_first_name} {$patient->parent_last_name}";

                foreach ($p_app as $appointment) {
                if (!isset($reports[$p_id])) {
                    $reports[$p_id] = [
                    'patient_name' => $full_name,
                    'report' => [],
                    ];
                }
                $reports[$p_id]['report'][]= ['content' => $appointment->report_path, 'app_id' => $appointment->id];
                }
            }
            return view('coach/profile', compact('coach','specialities','coachAppointments','reports'));
        }
     

    }
    public function getCoachesBySpeciality(Request $request)
    {
    
        $request->validate([
            'specialityId' => 'required|exists:specialities,id',
        ]);

        $specialityId = $request->specialityId;
        $coaches = User::where('speciality_id', $specialityId)->get();

        return response()->json([
            'success' => true,
            'coaches' => $coaches,
        ]);
    }
    public function allCoaches(Request $request)
    {
        $request->validate([
            'speciality_id' => 'required|exists:specialities,id',
            'date' => 'required|date',
            'startTime' => 'required|date_format:H:i',
            'endTime' => 'required|date_format:H:i',
        ]);

        $availableCoaches=[];
        $specialityId = $request->speciality_id;
        $date = $request->date;
        $startTime = $request->startTime;
        $endTime = $request->endTime;

        $transformedDate = strtotime($date);
        $weekDay = date('l', $transformedDate);
      


        $coaches = User::where('speciality_id',$specialityId)->get();
        Log::debug('coaches',[$coaches]);
        foreach ($coaches as $coach) {
        $planing = json_decode($coach->planning, true);
            foreach ($planing as $p_date => $intervals) {
                $planingWeekDay = date('l', strtotime($p_date));
                Log::debug('week day',[$weekDay]);
                Log::debug('planing Week Day',[$planingWeekDay]);
                if ($planingWeekDay === $weekDay) {
                    Log::debug('exist ',[$planingWeekDay,$weekDay]);
                    foreach ($intervals as $interval) {
                        $cStart = strtotime("$date " . $interval['startTime']);
                        $cEnd   = strtotime("$date " . $interval['endTime']);
                        $aStart = strtotime("$date " . $startTime);
                        $aEnd   = strtotime("$date " . $endTime);
                        

                        if ($cStart < $aEnd && $cEnd > $aStart) {

                            if (!Appointment::where('coach_id', $coach->id)
                                ->where('status', 'pending')
                                ->whereRaw("JSON_EXTRACT(appointment_planning, '$.\"$date\"') IS NOT NULL")
                                ->whereRaw("JSON_EXTRACT(appointment_planning, '$.\"$date\".startTime') = ?", [$interval['startTime']])
                                ->whereRaw("JSON_EXTRACT(appointment_planning, '$.\"$date\".endTime') = ?", [$interval['endTime']])
                                ->exists()) {
                                $availableCoaches[$coach->id] = $coach->full_name;
                                }
                        }
                    }
                }
            };

        }
     
  
        if (empty($availableCoaches)) {
            return response()->json([
                'success' => false,
                'message' => 'No available coaches found for the selected date and time.',
                'availableCoaches' => $availableCoaches,
            ]);
        }
        // dd($availableCoaches);

        return response()->json([
            'success' => true,
            'availableCoaches' => $availableCoaches,
        ]);
    }
    
}



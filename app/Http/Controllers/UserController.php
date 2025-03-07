<?php

namespace App\Http\Controllers;
use App\Models\Appointment;
use App\Models\Speciality;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function globalDashboard(Request $request){
        // Determine the current week start date from query parameters or default to this Monday.
        $currentWeekStart = $request->query('week') 
            ? \Carbon\Carbon::parse($request->query('week'))
            : \Carbon\Carbon::now()->startOfWeek(); // Assuming week starts on Monday

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
                        $organizedAppointments[$date][$coachId][] = [
                            'id'         => $appointment->id,
                            'status'=> $appointment->status,
                            'patient' => $patient_full_name,
                            'startTime'  => $times['startTime'], 
                            'endTime'    => $times['endTime'],  
                            'speciality' => $appointment->choosen_speciality,
                        ];
                    }
      
                }
            }
        }
        // dd($organizedAppointments);

        // Retrieve all coaches (adjust the query to your schema; here assuming a flag "is_coach")
        $coaches = User::where('role', 'coach')->get();

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
                'full_name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
                'tel' => 'required',
                'speciality_id' => 'required',
                'is_available' => 'required',
                "planning"=>'required',
                "role"=>"required"
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
            $user->save();


         

            // dd($user);
            return redirect()->route('user.index')->with('success', 'Coach added successfully!');
    }
    public function show(string $id)
    {
        
        $coach = User::with('speciality')->findOrFail($id);
        $specialities = Speciality::all();
        $coachAppointments = Appointment::with('patient')->where('coach_id', $id)->get();
        return view('user/coach_profile', compact('coach','specialities','coachAppointments'));

    }
    // public function showAdminProfile()
    // {
    //     $coach = Auth::with('speciality')->user();
    //     $id = $coach->id;
    //     $specialities = Speciality::all();
    //     $coachAppointments = Appointment::with('patient')->where('coach_id', $id)->get();
    //     return view('admin_profile', compact('coach','specialities','coachAppointments'));
    // }

 
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
        $request->validate([
            'full_name' => 'required',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($id)],
            'tel' => 'required',
            'speciality_id' => 'required',
            'is_available'=> 'required',
            'planning'=>'required',
        ],[
            'email.unique' => 'This email is already in use.'
        ]);

        if ($request->planning === '{}' ) {
            return redirect()->back()->withErrors(['planning' => 'planning is required']);
        }

        $user = User::find($id);
        $user->full_name = $request->full_name;
        $user->email = $request->email;
        $user->is_available = (int)$request->is_available;
        $user->speciality_id = $request->speciality_id;
        $user->phone = $request->tel;
        $user->planning = $request->planning;
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
            'planning'=>'required',

        ],[
            'email.unique' => 'This email is already in use.'
        ]);

        if ($request->planning === '{}' ) {
            return redirect()->back()->withErrors(['planning' => 'planning is required']);
        };

        $user = User::find($id);
        $user->full_name = $request->full_name;
        $user->email = $request->email;
        // $user->password = Hash::make($request->input('password'));
        $user->is_available = (int)$request->is_available;
        $user->speciality_id = $request->speciality_id;
        $user->phone = $request->tel;
        $user->planning = $request->planning;
        $user->save();
       return redirect()->route('coach_profile', $user->id)->with('success','updated successfully');
    }
    public function showCoachProfile()
    {
        $coach = Auth::user();
        $id = $coach->id;
        $specialities = Speciality::all();
        $coachAppointments = Appointment::with('patient')->where('coach_id', $id)->get();
        return view('coach/profile', compact('coach','specialities','coachAppointments'));
    }
}

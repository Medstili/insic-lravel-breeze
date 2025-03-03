<?php

namespace App\Http\Controllers;
use App\Models\Appointment;
use App\Models\Speciality;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
                'full_name' => 'required',
                'email' => 'required|email',
                'password' => 'required',
                'tel' => 'required',
                'speciality_id' => 'required',
                'is_available' => 'required',
                "planning"=>'required',
                "role"=>"required"
                
            ]);
        
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
            'email' => 'required|email',
            'tel' => 'required',
            'speciality_id' => 'required',
            'is_available'=> 'required',
            'planning'=>'required',

        ]);
        $user = User::find($id);
        $user->full_name = $request->full_name;
        $user->email = $request->email;
        // $user->password = Hash::make($request->input('password'));
        $user->is_available = (int)$request->is_available;
        $user->speciality_id = $request->speciality_id;
        $user->phone = $request->tel;
        $user->planning = $request->planning;
        $user->save();
       return redirect()->route('user.show', $user->id);
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
            'email' => 'required|email',
            'tel' => 'required',
            'speciality_id' => 'required',
            'is_available'=> 'required',
            'planning'=>'required',

        ]);
        $user = User::find($id);
        $user->full_name = $request->full_name;
        $user->email = $request->email;
        // $user->password = Hash::make($request->input('password'));
        $user->is_available = (int)$request->is_available;
        $user->speciality_id = $request->speciality_id;
        $user->phone = $request->tel;
        $user->planning = $request->planning;
        $user->save();
       return redirect()->route('coach_profile', $user->id);
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

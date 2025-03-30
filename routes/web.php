<?php

use App\Http\Controllers\SuggestedAppointments;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\NotificationControler;
use App\Http\Controllers\PatientController;
use Illuminate\Support\Facades\Route;
use  Illuminate\Support\Facades\Auth;


Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->role === 'coach') {
            return redirect()->route('coach_profile');
        } elseif (Auth::user()->role === 'admin') {
            return redirect()->route('global_dashboard');
        }
    }
    return view('auth.login');
});


Route::middleware(['auth','coach'])->group(function (){
    
    // coach profile route
    Route::get('/profile',[UserController::class, 'showCoachProfile'])->name('coach_profile');
    Route::get('/edit/{id}',[UserController::class, 'coach_edit'])->name('edit_profile');
    Route::put('/update/{id}',[UserController::class, 'coach_update'])->name('update_profile');

    // repport routes 
    Route::post('/coach-appointments/{id}/upload-report', [AppointmentController::class, 'uploadReport'])->name('coach-appointments.uploadReport');
    Route::delete('/coach-appointments/{id}/delete-report', [AppointmentController::class, 'deleteReport'])->name('coach-appointments.deleteReport');
    Route::get('/coach-appointments/{id}/download-report', [AppointmentController::class, 'downloadReport'])->name('coach-appointments.downloadReport');
    Route::get('/coach-appointments/{id}/view-report', [AppointmentController::class, 'viewReport'])->name('coach-appointments.viewReport');
    // apointment routes 
    Route::get('/appointment-details/{id}',[AppointmentController::class, 'show'])->name('appointment_details');
    Route::patch( '/coah-appointments/{id}/{status}/update-appointment-status', [AppointmentController::class, 'updateAppointmentStatus'])->name('coach-update-appointment-status');
    Route::patch('/appointment-update/{id}',[AppointmentController::class,'update'])->name('appointment_update');
    Route::get('appointment-edit/{id}', [AppointmentController::class,'edit'])->name('appointment_edit');
    Route::get('appointments-list/{id}',[AppointmentController::class , 'coach_appointments_list'])->name('appointments_list');

    //  patient routes
    Route::get('/patient-profile/{id}',[PatientController::class, 'coach_patient_profile'])->name('patient_profile');
    Route::get('/patients-list/{id}',[PatientController::class, 'coach_patinets_list'])->name('patients_list');
    Route::post('/coach-can-See/{id}',[PatientController::class , 'who_can_See'])->name('coach_who_can_See');
});

Route::middleware(['auth','admin'])->group(function () {
    Route::resource('user', UserController::class);
    Route::resource('appointment', AppointmentController::class);
    Route::resource('patient',PatientController::class);        
    Route::get('/global_dashboard', [AppointmentController::class, 'globalDashboard'])->name('global_dashboard');
    Route::get('/suggested-appointments', [SuggestedAppointments::class, 'suggestedAppointments'])->name('suggested-appointments');
    Route::post('/appointments/{id}/upload-report', [AppointmentController::class, 'uploadReport'])->name('appointments.uploadReport');
    Route::get('/appointments/{id}/download-report', [AppointmentController::class, 'downloadReport'])->name('appointments.downloadReport');
    Route::delete('/appointments/{id}/delete-report', [AppointmentController::class, 'deleteReport'])->name('appointments.deleteReport');
    Route::get('/appointments/{id}/view-report', [AppointmentController::class, 'viewReport'])->name('appointments.viewReport');
    Route::patch( '/appointments/{id}/{status}/update-appointment-status', [AppointmentController::class, 'updateAppointmentStatus'])->name('appointments.update-appointment-status');
    Route::post('/block-slot',[SuggestedAppointments::class , 'blockSlot'])->name('block_Slot');
    Route::get('/available-Patients',[PatientController::class , 'availablePatients'])->name('available_Patients');
    Route::get('/available-Coaches',[UserController::class , 'allCoaches'])->name('allCoaches');
    Route::post('/can-See/{id}',[PatientController::class , 'who_can_See'])->name('who_can_See');
    Route::patch('/update-Patient',[AppointmentController::class , 'update_app_patient'])->name('update.patient');
    Route::patch('/update-Planning',[AppointmentController::class , 'update_app_planning'])->name('update_app_planning');
    Route::patch('/update-sugg-planning',[SuggestedAppointments::class , 'update_sugg_app_planning'])->name('update_sugg_app_planning');
    Route::patch('/update-Suggested-Appointment-Patient',[SuggestedAppointments::class , 'update_Suggested_Appointment_Patient'])->name('update_sugg_Patient');
    Route::patch('/update-Suggested-Appointment-Coach',[SuggestedAppointments::class , 'update_Suggested_Appointment_Coach'])->name('update_sugg_Coach');
    Route::post('/Coaches-By-Speciality',[UserController::class , 'getCoachesBySpeciality'])->name('getCoachesBySpeciality');
    Route::post('/notifications/mark-as-read', [NotificationControler::class, 'markAsRead'])->name('notifications.mark-as-read');


});


require __DIR__.'/auth.php';

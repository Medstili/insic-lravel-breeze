<?php

namespace App\Console\Commands;

use App\Http\Controllers\SuggestedAppointments;
use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\SuggestedAppointment; // Ensure this import is correct and the class exists
use App\Services\AppointmentScheduler;
use Illuminate\Support\Facades\Log;

class StoreWeeklySuggestedAppointments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:store-weekly-suggested-appointments';
    protected $signature = 'app:store-suggestions';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Store suggested appointments for all patients for the current week, if not already stored.';

    /**
     * Execute the console command.
     */
    public function handle()
    {

         // Determine current week's boundaries (Monday to Sunday)
         $currentWeekStart = Carbon::now()->startOfWeek();
         $weekEnd = Carbon::now()->endOfWeek();
 
         // Check if suggestions exist for this week
         $existing = SuggestedAppointment::whereBetween('Date', [
             $currentWeekStart->format('Y-m-d'),
             $weekEnd->format('Y-m-d')
         ])->exists();
 
         if ($existing) {
             $this->info('Suggested appointments for this week already exist.');
             return 0;
         }
 
         // Instantiate your AppointmentScheduler and store suggestions
         $scheduler = new SuggestedAppointments();
         $scheduler->storeSuggestedAppointmentsForAllPatients($currentWeekStart->format('Y-m-d'), $weekEnd->format('Y-m-d'));
 
         $this->info('Suggested appointments stored for this week.');
         return 0;
     
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use App\Models\Patient;
use App\Models\User;
use App\Notifications\IncompleteWeeklyQuotaNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NotifyIncompleteWeeklyQuota extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:notify-incomplete-weekly-quota';
    

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify if patients did not complete their weekly quota';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::debug('notification',[]);
        // Define the current week (assuming Monday as start and Sunday as end)
        $weekStart = Carbon::now()->startOfWeek(); // Monday
        $weekEnd = Carbon::now()->endOfWeek(); // Sunday

        // Retrieve all patients (you may add filtering if needed)
        $patients = Patient::all();

        foreach ($patients as $patient) {
            // Calculate appointments count for the patient in the current week
            $appointmentCount = $patient->appointments()
                ->whereBetween('created_at', [$weekStart, $weekEnd])
                ->count();

            if ($appointmentCount < $patient->weekly_quota) {
                // Send notification via database channel.
                $admin = User::where('role', 'admin')->first();
                $admin->notify(new IncompleteWeeklyQuotaNotification(
                    $patient->patient_type == 'kid' || $patient->patient_type == 'young'
                        ? $patient->first_name . ' ' . $patient->last_name
                        : $patient->parent_first_name . ' ' . $patient->parent_last_name,
                    $appointmentCount,
                    $patient->weekly_quota
                ));
                
                // Notification::route('database', 'admin@example.com')
                //     ->notify(new IncompleteWeeklyQuotaNotification(
                //         $patient->patient_type == 'kid' || $patient->patient_type == 'young'
                //             ? $patient->first_name . ' ' . $patient->last_name
                //             : $patient->parent_first_name . ' ' . $patient->parent_last_name,
                //         $appointmentCount,
                //         $patient->weekly_quota
                //     ));
            }
        }

        $this->info('Notifications for incomplete weekly quotas sent successfully.');
    }
}

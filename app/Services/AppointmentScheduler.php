<?php

namespace App\Services;

use App\Models\Patient;
use App\Models\User;
use App\Models\Appointment;
use Carbon\Carbon;
use \App\Models\BlockedSlot;
use app\Http\Controllers\SuggestedAppointments;
use Illuminate\Support\Facades\Log;


class AppointmentScheduler
{
    /**
     * Generate weekly appointment suggestions for a given patient.
     *
     * @param Patient $patient
     * @param string $weekStart  (Y-m-d, e.g., Monday of the week)
     * @param string $weekEnd    (Y-m-d, e.g., Sunday of the week)
     * @return array  Array keyed by coach id, each containing suggested appointment slots.
     */
    public function  generateWeeklyAppointments(Patient $patient, string $weekStart, string $weekEnd): array
    {

        $weeklyQuota = $patient->weekly_quota;
        // Retrieve all assigned coaches (via pivot table "coach_patient")
        $coachAssignments = $patient->coaches; 
        $coachUsage = [];
       
        foreach ($coachAssignments as $coach) {
            $coachUsage[$coach->id] = (int)$coach->pivot->used_count;
        }
        
        // Initialize schedule array; one entry per coach.
        $schedule = [];
        foreach ($coachAssignments as $coach) {
            $schedule[$coach->id] = [
                'coach_id'      => $coach->id,
                'speciality_id' => $coach->speciality_id,
                // 'coach_name'    => $coach->full_name,
                'appointments'  => []  // This will hold appointment suggestions for this coach.
            ];
        }
        
        $priorities = json_decode($patient->priorities, true);
        if (!$priorities || empty($priorities)) {
            
            return  $schedule;
        }

        $priorityLevels = ['priority 1', 'priority 2', 'priority 3'];
        $usedDays = [];
        $assignedCount = 0;
        $weekIdentifier = $weekStart;

        foreach ($priorityLevels as $priorityKey) {
            if ($assignedCount >= $weeklyQuota) break;
            if (!isset($priorities[$priorityKey])) continue;

            $priorityData = $priorities[$priorityKey];
            $adjustedDates = [];
            foreach ($priorityData as $priorityDate => $intervals) {
                $priorityDateObj = Carbon::parse($priorityDate);
                $weekStartObj = Carbon::parse($weekStart);
                $weekEndObj = Carbon::parse($weekEnd);

                if ($priorityDateObj->between($weekStartObj, $weekEndObj, true)) {
                    $targetDate = $priorityDateObj->format('Y-m-d');
                } else {
                    $targetDate = $this->getNextOccurrenceInWeek(
                        $priorityDateObj->format('l'), 
                        $weekStart, 
                        $weekEnd,
                    
                    );
                    if (!$targetDate) continue;
                }

                // Store adjusted date with its intervals
                $adjustedDates[] = [
                    'date' => $targetDate,
                    'intervals' => $intervals
                ];

            }

            if (empty($adjustedDates)) {
               
                continue;
            }
    
            //Sort dates chronologically (oldest first)
            usort($adjustedDates, function ($a, $b) {
                return strcmp($a['date'], $b['date']);
            });

            Log::debug('adjustedDates',[$adjustedDates]);
            // Process sorted dates
            foreach ($adjustedDates as $adjustedDate) {
                $targetDate = $adjustedDate['date'];
                $intervals = $adjustedDate['intervals'];

                if (!$this->isDayAvailable($targetDate, $usedDays)) {
                    // Case 1: The day is too close to another assigned appointment.
                    continue;
                }

                foreach ($intervals as $interval) {
                    if (!$this->isDayAvailable($targetDate, $usedDays)) {
                        break; // Skip remaining intervals for this day if it's already used
                    }
                    if ($assignedCount >= $weeklyQuota) break;

                    $pStartTime = $interval['startTime'];
                    $pEndTime = $interval['endTime'];

                    foreach ($coachAssignments as $coach) {
                        if ($assignedCount >= $weeklyQuota) break;
                        if ($coachUsage[$coach->id] >= $coach->pivot->max_appointments) continue;


                        $freeInterval = $this->isTimeAvailableForPatient(
                            $coach->id,
                            $patient->id,
                            $targetDate,
                            $pStartTime,
                            $pEndTime,
                            $weekIdentifier
                        );

                        if ($freeInterval !== null) {
                            $appointmentSlot = [
                                'patient_id' => $patient->id,
                                'date' => $targetDate,
                                'startTime' => $freeInterval['startTime'],
                                'endTime' => $freeInterval['endTime'],
                                'priority' => $priorityKey,
                            ];
                            $schedule[$coach->id]['appointments'][] = $appointmentSlot;
                            $coachUsage[$coach->id]++;
                            $assignedCount++;
                            $usedDays[] = $targetDate; 
                           
                            break;
                        }
                    }

  
                }
            }
        }
   
        $allCoachesReached = true;
        foreach ($coachAssignments as $coach) {
            if ($coachUsage[$coach->id] < $coach->pivot->max_appointments) {
                $allCoachesReached = false;
                break;
            }
        }

        if ($allCoachesReached && $assignedCount < $weeklyQuota) {
            // Reset usage counts for each coach (persistently or just for this calculation)
            foreach ($coachAssignments as $coach) {
                $coachUsage[$coach->id] = 0;
            }
            foreach ($priorityLevels as $priorityKey) {
                if ($assignedCount >= $weeklyQuota) break;
                if (!isset($priorities[$priorityKey])) continue;
                
                $priorityData = $priorities[$priorityKey];
                $adjustedDates = [];
                foreach ($priorityData as $priorityDate => $intervals) {
                    $priorityDateObj = Carbon::parse($priorityDate);
                    $weekStartObj = Carbon::parse($weekStart);
                    $weekEndObj = Carbon::parse($weekEnd);
        
                    if ($priorityDateObj->between($weekStartObj, $weekEndObj, true)) {
                        $targetDate = $priorityDateObj->format('Y-m-d');
                    } else {
                        $targetDate = $this->getNextOccurrenceInWeek(
                            $priorityDateObj->format('l'), 
                            $weekStart, 
                            $weekEnd
                        );
                        if (!$targetDate) continue;
                    }
        
                    $adjustedDates[] = [
                        'date' => $targetDate,
                        'intervals' => $intervals
                    ];
                }
        
                usort($adjustedDates, function ($a, $b) {
                    return strcmp($a['date'], $b['date']);
                });
        
                foreach ($adjustedDates as $adjustedDate) {
                    $targetDate = $adjustedDate['date'];
                    $intervals = $adjustedDate['intervals'];
        
                    if (!$this->isDayAvailable($targetDate, $usedDays)) continue;
        
                    foreach ($intervals as $interval) {
                        if ($assignedCount >= $weeklyQuota) break;
        
                        $pStartTime = $interval['startTime'];
                        $pEndTime = $interval['endTime'];
        
                        foreach ($coachAssignments as $coach) {
                            if ($assignedCount >= $weeklyQuota) break;
                            // Now that we reset usage, we don't check capacity here.
        
                            $freeInterval = $this->isTimeAvailableForPatient(
                                $coach->id,
                                $patient->id,
                                $targetDate,
                                $pStartTime,
                                $pEndTime,
                                $weekIdentifier
                            );
        
                            if ($freeInterval !== null) {
                                $appointmentSlot = [
                                    'patient_id' => $patient->id,
                                    'date' => $targetDate,
                                    'startTime' => $freeInterval['startTime'],
                                    'endTime' => $freeInterval['endTime'],
                                    'priority' => $priorityKey,
                                ];
                                $schedule[$coach->id]['appointments'][] = $appointmentSlot;
                                $coachUsage[$coach->id]++;
                                $assignedCount++;
                                $usedDays[] = $targetDate;
                                break;
                            }
                        }
                    }
                }
            }
        }

        return $schedule;
    }

    /**
     * Get the next occurrence of a given weekday (e.g., "Monday") within the week defined by $weekStart and $weekEnd.
     * Returns the date (Y-m-d) if found; otherwise, returns null.
     */
    private function getNextOccurrenceInWeek(string $weekday, string $weekStart, string $weekEnd): ?string
    {
        $start = Carbon::parse($weekStart);
        $end   = Carbon::parse($weekEnd);
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            if ($date->format('l') === $weekday) {
                return $date->format('Y-m-d');
            }
        }
        return null;
    }
    
    /**
     * Check if a given date is available based on an array of used dates.
     * For a minimum gap rule, if any used date is less than 2 days apart, return false.
     */
    private function isDayAvailable(string $date, array $usedDays): bool
    {
        foreach ($usedDays as $used) {
            $diff = abs(Carbon::parse($date)->diffInDays(Carbon::parse($used)));
            if ($diff < 2) { // less than 2 days apart means not allowed.
                return false;
            }
        }
        return true;
    }
    
    private function isTimeAvailableForPatient($coachId, $patientId, $date, $patientStartTime, $patientEndTime,$weekIdentifier)
    {
        // Convert patient's requested times to timestamps.
        $pStart = strtotime("$date $patientStartTime");
        $pEnd   = strtotime("$date $patientEndTime");

        // *** Check if the patient already has an overlapping appointment ***
        $existingPatientAppointments = Appointment::where('patient_id', $patientId)
            ->where('status', 'pending')
            ->where('coach_id', $coachId)
            ->whereRaw("JSON_EXTRACT(appointment_planning, '$.\"$date\"') IS NOT NULL")
            ->get();

        foreach ($existingPatientAppointments as $appt) {
            $apptPlanning = json_decode($appt->appointment_planning, true);
            if (isset($apptPlanning[$date])) {
                $aStart = strtotime("$date " . $apptPlanning[$date]['startTime']);
                $aEnd   = strtotime("$date " . $apptPlanning[$date]['endTime']);
                if ($aEnd > $pStart && $aStart < $pEnd) {
                    // Patient conflict: return busy slot with booked times.
                    return [
                        'startTime' => $apptPlanning[$date]['startTime'],
                        'endTime'   => $apptPlanning[$date]['endTime'],
                    ];
                }
            }
        }

        // Subtract overlapping coach appointments from candidate interval ===
        // Start with the full requested candidate interval.

        $allConflicts = $this->getTimeConflicts($coachId, $date, $weekIdentifier, $pStart, $pEnd);

        $candidateIntervals = [
            ['start' => $pStart, 'end' => $pEnd]
        ];

        // $existingCoachAppointments = Appointment::where('coach_id', $coachId)
        //     ->where('status', 'pending')
        //     ->whereRaw("JSON_EXTRACT(appointment_planning, '$.\"$date\"') IS NOT NULL")
        //     ->get();

        foreach ($allConflicts as $conflict) {
            // $apptPlanning = json_decode($appt->appointment_planning, true);
            // if (isset($apptPlanning[$date])) {
            //     $conflictStart = strtotime("$date " . $apptPlanning[$date]['startTime']);
            //     $conflictEnd   = strtotime("$date " . $apptPlanning[$date]['endTime']);
                $newCandidateIntervals = [];
                foreach ($candidateIntervals as $interval) {
                    // If conflict is completely before or after this candidate, keep the interval unchanged.
                    if ($conflict['end'] <= $interval['start'] || $conflict['start'] >= $interval['end']) {
                        $newCandidateIntervals[] = $interval;
                    } else {
                        // There is overlap.
                        // If there is free time before the conflict starts, add that portion.
                        if ($conflict['start']  > $interval['start']) {
                            $newCandidateIntervals[] = [
                                'start' => $interval['start'],
                                'end'   => $conflict['start'] 
                            ];
                        }
                        // If there is free time after the conflict ends, add that portion.
                        if ($conflict['end']< $interval['end']) {
                            $newCandidateIntervals[] = [
                                'start' =>$conflict['end'],
                                'end'   => $interval['end']
                            ];
                        }
                    }
                }
                $candidateIntervals = $newCandidateIntervals;
            // }
        }



        // If no candidate intervals remain after subtracting coach conflicts, return null.

        if (empty($candidateIntervals)) {
            return null;
        }

        // Now retrieve the coach's working schedule.
        $coach = User::find($coachId);
        if (!$coach) return null;
        $coachPlanning = json_decode($coach->planning, true);

        // Find the coach's working day that matches the weekday of $date.
        $workingDay = null;
        foreach ($coachPlanning as $d => $intervals) {
            if (Carbon::parse($d)->format('l') === Carbon::parse($date)->format('l')) {
                $workingDay = $d;
                break;
            }
        }
        if (!$workingDay) return null;

        // Calculate the overlapping intervals between each candidate interval and the coach's working intervals.
        $coachIntervals = $coachPlanning[$workingDay];
        $availableIntervals = [];
        foreach ($coachIntervals as $interval) {
            $coachStart = strtotime("$date " . $interval['startTime']);
            $coachEnd   = strtotime("$date " . $interval['endTime']);
            foreach ($candidateIntervals as $cand) {
                $overlapStart = max($cand['start'], $coachStart);
                $overlapEnd   = min($cand['end'], $coachEnd);
                if ($overlapEnd > $overlapStart) {
                    $availableIntervals[] = [
                        'start' => $overlapStart,
                        'end'   => $overlapEnd,
                    ];
                }
            }
        }
        
        if (empty($availableIntervals)) return null;

        // Filter available intervals by duration.
        $minDuration = 45 * 60; // 45 minutes in seconds.
        $maxDuration = 60 * 60; // 60 minutes in seconds.
        foreach ($availableIntervals as $free) {
            $duration = $free['end'] - $free['start'];
            if ($duration >= $minDuration) {
                $slotStart = $free['start'];
                $slotEnd = $free['end'];
                if ($duration > $maxDuration) {
                    $slotEnd = $slotStart + $maxDuration;
                }
                return [
                    'startTime' => date('H:i', $slotStart),
                    'endTime'   => date('H:i', $slotEnd),
                    // 'busy'      => false
                ];
            }
        }
        
        return null;
    }

    private function getTimeConflicts($coachId, $date, $weekIdentifier, $pStart, $pEnd)
    {


        // Get coach appointments
        $coachAppointments = Appointment::where('coach_id', $coachId)
            ->where(function ($query) {
                $query->where('status', 'pending')
                    ->orWhere(function ($q) {
                        $q->where('status', 'cancel')
                            ->where('cancelledBy', 'coach');
                    });
            })
            ->whereRaw("JSON_EXTRACT(appointment_planning, '$.\"$date\"') IS NOT NULL")
            ->get()
            ->map(function ($appt) use ($date) {
                $planning = json_decode($appt->appointment_planning, true);
                return [
                    'start' => strtotime("$date ".$planning[$date]['startTime']),
                    'end' => strtotime("$date ".$planning[$date]['endTime'])
                ];
            });

        // Combine all conflicts
        return collect($coachAppointments);


    }
}
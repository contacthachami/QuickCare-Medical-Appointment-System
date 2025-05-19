<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Schedule;
use App\Models\Speciality;
use App\Models\User;
use App\Models\Rating;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\TravelRecordsExport;
use App\Exports\AppointmentsExport;
use App\Exports\DoctorPatientsExport;
use Maatwebsite\Excel\Facades\Excel;

class DoctorController extends Controller
{
    //Views's Functions

    public function index()
    {
        $appointments = Auth::user()->doctor->Appointments;

        $schedule = Auth::user()->doctor->schedules;

        $patientIds = Auth::user()->doctor->Appointments->pluck('patient_id')->unique();

        $patients = Patient::whereIn('id', $patientIds)->get();

        $upcommingAppointments = Appointment::where('doctor_id', Auth::user()->doctor->id)
        ->where('appointment_date', '>', Carbon::now())->get();

        $recentVisits = Appointment::where('doctor_id', Auth::user()->doctor->id)
        ->where('appointment_date', '<', Carbon::now())
        ->where('status', 'Approved')
        ->get();

        $ratings = Rating::where('doctor_id', Auth::user()->doctor->id)->get();

        return view('panels.doctor.index')->with('appointments', $appointments)->with('schedule', $schedule)
        ->with('patients', $patients)->with('upcommingAppointments', $upcommingAppointments)
        ->with('recentVisits', $recentVisits)
        ->with('ratings', $ratings);
    }

    public function appointments()
    {
        // Get appointments using pagination
        $doctor_id = Auth::user()->doctor->id;
        
        // Handle export requests
        if (request()->has('travel') && request()->has('export')) {
            $exportType = request()->get('export');
            $doctorAppointments = Auth::user()->doctor->Appointments;
            $completedAppointments = $doctorAppointments->filter(function($appointment) {
                return $appointment->check_in_time && $appointment->check_out_time;
            })->sortByDesc('appointment_date');
            
            if ($exportType === 'csv') {
                return $this->exportTravelRecordsCSV($completedAppointments);
            } elseif ($exportType === 'pdf') {
                return $this->exportTravelRecordsPDF($completedAppointments);
            }
        }
        
        // Redirect to travel tracking page if travel parameter is present
        if (request()->has('travel')) {
            return $this->travelTracking();
        }
        
        // Use paginate instead of get for appointments
        $appointments = Appointment::where('doctor_id', $doctor_id)
            ->orderBy('appointment_date', 'desc')
            ->paginate(10);
        
        return view('panels.doctor.appointments')->with(compact('appointments'));
    }

    public function schedule()
    {
        $doctor = Auth::user()->doctor;
        $schedule = Auth::user()->doctor->schedules;
        return view('panels.doctor.schedule')->with(compact('schedule'))->with('doctor', $doctor);
    }

    public function editSchedule($id)
    {

        $schedule = Schedule::find($id);

        if (!$schedule) {
            abort(404);
        }

        return view('panels.doctor.CRUD.edit_schedule')->with(compact('schedule'));
    }

    public function editAppointmentView($id)
    {

        $appointment = Appointment::find($id);

        if (!$appointment) {
            abort(404);
        }

        return view('panels.doctor.CRUD.edit_appointment')->with(compact('appointment'));
    }

    public function mypatients(){

        $patientIds = Auth::user()->doctor->Appointments->pluck('patient_id')->unique();

        $patients = Patient::whereIn('id', $patientIds)->get();
        $appointments = Appointment::whereIn('patient_id', $patientIds)->get();

        return view('panels.doctor.mypatients', with(compact('patients', 'appointments')));

    }

    public function patientView($id){

        $patient = Patient::find($id);
        $appointments = Appointment::where('patient_id', $id)->get();
        return view('panels.doctor.CRUD.patient_view')->with(compact('patient'))->with(compact('appointments'));
    }


    public function bookAppointmentView($id){
        $doctors = Doctor::all();
        $patient = Patient::find($id);
        $specialities = Speciality::all();
        $schedules = Schedule::all();
        return view('panels.doctor.CRUD.patient_book')->with(compact('patient'))->with(compact('specialities'))->with(compact('schedules'))
        ->with(compact('doctors'));
    }

    public function appointmentDetails($id){

        $appointment = Appointment::find($id);
        $patient = Patient::find($appointment->patient_id);

        return view('panels.doctor.CRUD.appointment_details')->with(compact('appointment'))->with(compact('patient'));
    }

    public function reviews(){

        $ratings = Rating::where('doctor_id', Auth::user()->doctor->id)->get();

        return view('panels.doctor.reviews')->with(compact('ratings'));
    }

    //Operations's Functions

    public function deleteSchedule($id)
    {
        // Find the schedule by its ID
        $schedule = Schedule::find($id);

        // If the schedule doesn't exist, redirect back with an error message
        if (!$schedule) {
            return redirect()->back()->with('error', 'Schedule not found.');
        }

        $appointments = Appointment::where('schedule_id', $id)->get();
        if ($appointments) {
            foreach ($appointments as $appointment) {
                $appointment->delete();
            }
        }

        // Delete the schedule
        $schedule->delete();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Schedule deleted successfully.');
    }

    public function updateSchedule(Request $request, $id)
    {
        $schedule = Schedule::find($id);
        if (!$schedule) {
            abort(404);
        }

        $validator = Validator::make($request->all(), [
        'day' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
        'start_time' => 'required',
        'end_time' => 'required|after:start_time',
    ], [
        'day.required' => 'Please select a day.',
        'day.in' => 'Invalid day selected.',
        'start_time.required' => 'Please enter a start time.',

        'end_time.required' => 'Please enter an end time.',
        'end_time.date_format' => 'Invalid end time format. ',
        'end_time.after' => 'End time must be after start time.',

        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }


        $schedule->day = ucfirst(strtolower($request->input('day')));
        $schedule->start = $request->input('start_time');
        $schedule->end = $request->input('end_time');
        $schedule->save();
        return redirect()->route('doctor.schedule')->with('success', 'Schedule updated successfully.');
    }




    public function getAppointmentDetails($id)
    {
        $appointment = Appointment::findOrFail($id);

        $data = [
            'id' => $appointment->id,
            'title' => $appointment->patient->user->name,
            'start' => $appointment->appointment_date,
            'extendedProps' => [
                'patientName' => $appointment->patient->user->name,
                'appointmentDate' => $appointment->appointment_date,
                'reason' => $appointment->reason,
                'doctorName' => $appointment->doctor->user->name,
            ]
        ];

        return response()->json($data);
    }




    public function updateAppointment(Request $request, $id)
    {

        $appointment = Appointment::find($id);
        if (!$appointment) {
            return redirect()->back()->with('error', 'Appointment not found.');
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:Pending,Approved,Cancelled,Expired',
            'doctor_comment' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $appointment->status = ucfirst(strtolower($request->input('status')));
        $appointment->doctor_comment = $request->input('doctor_comment');
        $appointment->save();

        return redirect()->route('doctor.appointments')->with('success', 'Appointment updated successfully.');
    }

    public function getSchedules()
    {
        $user = Auth::user();
        $doctor = $user->doctor;
        
        // Retrieve all available schedules for the doctor
        $schedules = Schedule::where('doctor_id', $doctor->id)
            ->where('status', 'false')
            ->get();
            
        // Transform schedules for use in the calendar
        $events = [];
        foreach ($schedules as $schedule) {
            // Parse times for this specific schedule
            $startTime = Carbon::parse($schedule->start);
            $endTime = Carbon::parse($schedule->end);
            
            // Format times in a clean, readable format
            $formattedStart = $startTime->format('g:i A');
            $formattedEnd = $endTime->format('g:i A');
            
            // If this schedule has a specific date, use that
            if ($schedule->specific_date) {
                $eventDate = Carbon::parse($schedule->specific_date);
                
                $events[] = [
                    'id' => $schedule->id,
                    'title' => 'Available: ' . $formattedStart . ' - ' . $formattedEnd,
                    'start' => $eventDate->copy()->setTime($startTime->hour, $startTime->minute)->toDateTimeString(),
                    'end' => $eventDate->copy()->setTime($endTime->hour, $endTime->minute)->toDateTimeString(),
                    'day' => $schedule->day
                ];
            } else {
                // For recurring schedules, find the next few occurrences of this day of the week
                $dayOfWeek = $schedule->day;
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->addWeeks(4)->endOfWeek(); // Show 4 weeks of recurring schedules
                
                $currentDate = clone $startDate;
                
                while ($currentDate->lte($endDate)) {
                    // Check if the current date matches the schedule day
                    if ($currentDate->englishDayOfWeek == $dayOfWeek) {
                        // Create an event for this recurring schedule on this matching date
                        $events[] = [
                            'id' => $schedule->id,
                            'title' => 'Available: ' . $formattedStart . ' - ' . $formattedEnd,
                            'start' => $currentDate->copy()->setTime($startTime->hour, $startTime->minute)->toDateTimeString(),
                            'end' => $currentDate->copy()->setTime($endTime->hour, $endTime->minute)->toDateTimeString(),
                            'day' => $dayOfWeek
                        ];
                    }
                    
                    // Move to the next day
                    $currentDate->addDay();
                }
            }
        }
        
        return response()->json($events);
    }

    public function add_schedule(Request $request, $id)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'start_times' => 'nullable|array', // Validate 'start_times' as array
                'start_times.*' => 'nullable|array', // Each item inside 'start_times' should be an array
                'start_times.*.*' => 'nullable|date_format:H:i', // Validate as time format (HH:MM)
                'specific_date' => 'nullable|array', // Validate specific_date as array
                'specific_date.*' => 'nullable|date', // Each specific date should be a valid date
            ]);

            // Check if 'start_times' is empty
            if (empty($validatedData['start_times'])) {
                return redirect()->back()->with('error', 'Please select at least one time slot.');
            }

            // Iterate through the submitted data and store in the database
            foreach ($validatedData['start_times'] as $day => $startTimes) {
                if ($startTimes) { // Check if there are start times for this day
                    // Get the specific date for this day if provided
                    $specificDate = isset($validatedData['specific_date'][$day]) ? $validatedData['specific_date'][$day] : null;
                    
                    foreach ($startTimes as $startTime) {
                        // Check if there's an existing record with the same doctor_id, day, start time, and specific date
                        $query = Schedule::where('doctor_id', $id)
                            ->where('day', ucfirst($day))
                            ->where('start', $startTime);
                            
                        if ($specificDate) {
                            $query->where('specific_date', $specificDate);
                        } else {
                            $query->whereNull('specific_date');
                        }
                        
                        $existingSchedule = $query->exists();

                        // If there's no existing record, then insert the new schedule
                        if (!$existingSchedule) {
                            // Create a new Schedule instance
                            $schedule = new Schedule();
                            $schedule->start = $startTime;
                            
                            // Set the specific date if provided
                            if ($specificDate) {
                                $schedule->specific_date = $specificDate;
                            }

                            // Set the end time to 30 minutes later than the start time
                            $schedule->end = date('H:i', strtotime('+30 minutes', strtotime($startTime)));

                            $schedule->day = ucfirst($day); // Capitalize the day name
                            $schedule->doctor_id = $id;
                            $schedule->status = 'false';
                            $schedule->save();
                        } else {
                            return redirect()->back()->with('error', 'Schedule already Exist');
                        }
                    }
                }
            }

            // Redirect back or do whatever you want after saving
            return redirect()->back()->with('success', 'Schedule saved successfully');
        } catch (\Exception $e) {
            // Log or display the error message
            dd($e->getMessage());
        }
    }



    public function book(Request $request, $P_ID)
    {
        $reason_for_appointment = $request->input('reason');
        $appointment_date = $request->input('appointment_datee');
        $schedule_id = $request->input('appointment_time');
        $doctor_id = $request->input('doctor_id');

        // Validating form inputs
        $validator = Validator::make($request->all(), [
            'reason' => 'required|string|max:255',
            'appointment_datee' => 'required|date',
            'appointment_time' => 'required|exists:schedules,id', // Ensure the selected schedule ID exists in the database
        ]);

        if ($validator->fails()) {
            return redirect()->route('doctor.CRUD.patient.book',['id' => $P_ID])->with('error', 'Appointment booking failed');
        }

        // Creating the appointment
        $appointment = Appointment::create([
            'reason' => $reason_for_appointment,
            'appointment_date' => $appointment_date,
            'schedule_id' => $schedule_id, // Storing the selected schedule ID
            'doctor_id' => $doctor_id,
            'doctor_comment' => 'none',
            'patient_id' => $P_ID,
            'status' => 'Pending',
        ]);

        // If appointment is successfully created
        if ($appointment) {
            // Update the schedule to mark the booked time slot as unavailable
            $schedule = Schedule::find($schedule_id);
            if ($schedule) {
                $schedule->update(['status' => 'true']);
            }
            return redirect()->route('doctor.mypatients')->with('success', 'Appointment booked successfully');
        } else {
            return redirect()->route('doctor.CRUD.patient.book')->with('error', 'Appointment booking failed');
        }
    }



    public function getAvailableHours(Request $request)
    {
        // Retrieve the date from the request
        $date = $request->input('date');
        $doctor_id = $request->input('doctor_id');
        
        // Convert the date to day of the week (e.g., Monday, Tuesday)
        $dayOfWeek = Carbon::parse($date)->format('l');

        // Retrieve available hours for the selected date and day of the week
        $query = Schedule::where('doctor_id', $doctor_id)
            ->where('status', 'false')
            ->where(function($query) use ($date, $dayOfWeek) {
                // Either match the day of week with no specific date
                $query->where(function($q) use ($dayOfWeek) {
                    $q->where('day', $dayOfWeek)
                      ->whereNull('specific_date');
                })
                // OR match the specific date exactly
                ->orWhere('specific_date', $date);
            })
            ->select('id', 'start'); // Selecting both ID and start time
        
        $availableHours = $query->get();

        // Return the available hours as JSON response
        return response()->json($availableHours);
    }

    public function getAppointments(Request $request)
    {
        // Retrieve the date from the request
        $date = $request->input('date');
        $doctor_id = $request->input('doctor_id');
        // Retrieve appointments for the specified doctor and date
        $appointments = Appointment::where('doctor_id', $doctor_id)
            ->whereDate('appointment_date', $date)
            ->get();

        // Return appointments as JSON response
        return response()->json($appointments);
    }


    public function getAppointmentsForCalendar(Request $request)
    {
        $appointments = Appointment::where('doctor_id', Auth::user()->doctor->id)->get();

        $data = [];
        foreach ($appointments as $appointment) {
            $data[] = [
                'id' => $appointment->id,
                'title' => $appointment->patient->user->name,
                'start' => $appointment->appointment_date,
                'extendedProps' => [
                    'patientName' => $appointment->patient->user->name,
                    'appointmentDate' => $appointment->appointment_date,
                    'reason' => $appointment->reason ?? 'Not specified',
                    'doctorName' => $appointment->doctor->user->name,
                ]
            ];
        }

        return response()->json($data);
    }

    public function checkIn(Request $request, $id)
    {
        $appointment = Appointment::find($id);
        
        if (!$appointment) {
            return redirect()->back()->with('error', 'Appointment not found.');
        }
        
        // Check if user is the doctor for this appointment
        if ($appointment->doctor_id != Auth::user()->doctor->id) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }
        
        // Record check-in time
        $appointment->check_in_time = now();
        $appointment->save();
        
        return redirect()->back()->with('success', 'Check-in time recorded successfully.');
    }
    
    public function checkOut(Request $request, $id)
    {
        $appointment = Appointment::find($id);
        
        if (!$appointment) {
            return redirect()->back()->with('error', 'Appointment not found.');
        }
        
        // Check if user is the doctor for this appointment
        if ($appointment->doctor_id != Auth::user()->doctor->id) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }
        
        // Check if check-in was recorded
        if (!$appointment->check_in_time) {
            return redirect()->back()->with('error', 'Please check-in first.');
        }
        
        // Record check-out time
        $appointment->check_out_time = now();
        
        // Calculate travel time in minutes
        $checkInTime = \Carbon\Carbon::parse($appointment->check_in_time);
        $checkOutTime = \Carbon\Carbon::parse($appointment->check_out_time);
        $travelTimeMinutes = $checkOutTime->diffInMinutes($checkInTime);
        $appointment->travel_time_minutes = $travelTimeMinutes;
        
        $appointment->save();
        
        $travelTimeMessage = $travelTimeMinutes >= 60 
            ? floor($travelTimeMinutes / 60) . 'h ' . ($travelTimeMinutes % 60) . 'm'
            : $travelTimeMinutes . ' minutes';
            
        return redirect()->back()->with('success', 'Check-out time recorded. Travel time: ' . $travelTimeMessage . '.');
    }

    /**
     * Export travel records to CSV
     */
    private function exportTravelRecordsCSV($appointments)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="travel_records_' . now()->format('Y-m-d') . '.csv"',
        ];
        
        $callback = function() use ($appointments) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'Date', 
                'Patient Name', 
                'Appointment Time', 
                'Check-In Time', 
                'Check-Out Time', 
                'Travel Time (minutes)',
                'Location'
            ]);
            
            // Add CSV data
            foreach ($appointments as $appointment) {
                fputcsv($file, [
                    Carbon::parse($appointment->appointment_date)->format('Y-m-d'),
                    $appointment->patient->user->name,
                    Carbon::parse($appointment->appointment_date)->format('h:i A'),
                    Carbon::parse($appointment->check_in_time)->format('h:i A'),
                    Carbon::parse($appointment->check_out_time)->format('h:i A'),
                    $appointment->travel_time_minutes,
                    $appointment->patient->user->address->ville . ', ' . $appointment->patient->user->address->rue
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Export travel records to PDF
     */
    private function exportTravelRecordsPDF($appointments)
    {
        $data = [
            'appointments' => $appointments,
            'doctor' => Auth::user()->doctor,
            'totalTime' => $appointments->sum('travel_time_minutes'),
            'avgTime' => $appointments->count() > 0 ? round($appointments->sum('travel_time_minutes') / $appointments->count(), 1) : 0,
            'exportDate' => now()->format('Y-m-d H:i:s')
        ];
        
        $pdf = Pdf::loadView('exports.travel-records-pdf', $data);
        
        return $pdf->download('travel_records_' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Display the dedicated travel tracking page
     */
    public function travelTracking()
    {
        // Get the doctor's ID
        $doctor_id = Auth::user()->doctor->id;
        
        // Get paginated appointments for the travel history table with all necessary relationships
        $appointments = Appointment::where('doctor_id', $doctor_id)
            ->with(['patient.user.address', 'schedule'])
            ->orderBy('appointment_date', 'desc')
            ->paginate(10);
        
        // Get all appointments for calculations
        $allAppointments = Auth::user()->doctor->Appointments()->with(['patient.user.address', 'schedule'])->get();
        
        // Filter completed appointments (those with both check-in and check-out times)
        $completedAppointments = $allAppointments->filter(function($appointment) {
            return $appointment->check_in_time && $appointment->check_out_time;
        })->sortByDesc('appointment_date');
        
        // Calculate total travel time in minutes
        $totalTravelTime = $completedAppointments->sum('travel_time_minutes');
        
        // Calculate average travel time per appointment
        $avgTravelTime = $completedAppointments->count() > 0 
            ? round($totalTravelTime / $completedAppointments->count(), 1) 
            : 0;
        
        // Count total completed trips
        $totalTrips = $completedAppointments->count();
        
        // Calculate travel time for the last 7 days
        $lastWeek = Carbon::now()->subDays(7);
        $lastWeekAppointments = $completedAppointments->filter(function($appointment) use ($lastWeek) {
            return Carbon::parse($appointment->appointment_date)->isAfter($lastWeek);
        });
        $lastWeekTravelTime = $lastWeekAppointments->sum('travel_time_minutes');
        
        // Get active appointment (currently traveling)
        $activeAppointment = $allAppointments->first(function($appointment) {
            return $appointment->check_in_time && !$appointment->check_out_time;
        });
        
        // Rename to match the variable name in the view
        $activeTravelAppointment = $activeAppointment;
        
        // Get today's appointments
        $today = Carbon::today();
        $todaysAppointments = $allAppointments->filter(function($appointment) use ($today) {
            return Carbon::parse($appointment->appointment_date)->isSameDay($today);
        })->sortBy('appointment_date');
        
        // Check if we have active appointments today
        $hasActiveAppointments = $todaysAppointments->count() > 0;
        
        return view('panels.doctor.travel-tracking', compact(
            'appointments',
            'completedAppointments',
            'totalTravelTime',
            'avgTravelTime',
            'totalTrips',
            'lastWeekTravelTime',
            'activeAppointment',
            'activeTravelAppointment',
            'todaysAppointments',
            'hasActiveAppointments'
        ));
    }
    
    /**
     * Export travel records in specified format
     */
    public function exportTravelRecords($format)
    {
        $appointments = Auth::user()->doctor->Appointments;
        $completedAppointments = $appointments->filter(function($appointment) {
            return $appointment->check_in_time && $appointment->check_out_time;
        })->sortByDesc('appointment_date');
        
        if ($format === 'csv') {
            return $this->exportTravelRecordsCSV($completedAppointments);
        } elseif ($format === 'pdf') {
            return $this->exportTravelRecordsPDF($completedAppointments);
        } elseif ($format === 'excel') {
            return $this->exportTravelRecordsExcel($completedAppointments);
        }
        
        return redirect()->back()->with('error', 'Invalid export format');
    }

    /**
     * Export travel records to Excel
     */
    private function exportTravelRecordsExcel($appointments)
    {
        return Excel::download(new TravelRecordsExport(), 'travel_records_' . now()->format('Y-m-d') . '.xlsx');
    }

    /**
     * Export all appointments to Excel
     */
    public function exportAppointments()
    {
        return Excel::download(new AppointmentsExport(), 'my_appointments_' . now()->format('Y-m-d') . '.xlsx');
    }

    /**
     * Get all available schedule slots for the calendar
     */
    public function getAllSchedulesForCalendar()
    {
        $user = Auth::user();
        $doctor = $user->doctor;
        
        // Retrieve all available schedules for the doctor
        $schedules = Schedule::where('doctor_id', $doctor->id)
            ->where('status', 'false')
            ->get();
            
        // Transform schedules for use in the calendar
        $events = [];
        foreach ($schedules as $schedule) {
            // Parse times for this specific schedule
            $startTime = Carbon::parse($schedule->start);
            $endTime = Carbon::parse($schedule->end);
            
            // Format times in a clean, readable format
            $formattedStart = $startTime->format('g:i A');
            $formattedEnd = $endTime->format('g:i A');
            
            // If this schedule has a specific date, use that
            if ($schedule->specific_date) {
                $eventDate = Carbon::parse($schedule->specific_date);
                
                $events[] = [
                    'id' => $schedule->id,
                    'title' => 'Available: ' . $formattedStart . ' - ' . $formattedEnd,
                    'start' => $eventDate->copy()->setTime($startTime->hour, $startTime->minute)->toDateTimeString(),
                    'end' => $eventDate->copy()->setTime($endTime->hour, $endTime->minute)->toDateTimeString(),
                    'day' => $schedule->day
                ];
            } else {
                // For recurring schedules, find the next occurrence of this day of the week
                $dayOfWeek = $schedule->day;
                $nextDate = Carbon::now()->startOfDay();
                
                while ($nextDate->englishDayOfWeek !== $dayOfWeek) {
                    $nextDate->addDay();
                }
                
                // Create a single event for this specific schedule on the next matching date
                $events[] = [
                    'id' => $schedule->id,
                    'title' => 'Available: ' . $formattedStart . ' - ' . $formattedEnd,
                    'start' => $nextDate->copy()->setTime($startTime->hour, $startTime->minute)->toDateTimeString(),
                    'end' => $nextDate->copy()->setTime($endTime->hour, $endTime->minute)->toDateTimeString(),
                    'day' => $dayOfWeek
                ];
            }
        }
        
        return response()->json($events);
    }

    /**
     * Print appointments in a professional format
     */
    public function printAppointments()
    {
        // Increase execution time limit for PDF generation
        ini_set('max_execution_time', 300);
        set_time_limit(300);
        
        $doctor_id = Auth::user()->doctor->id;
        $appointments = Appointment::where('doctor_id', $doctor_id)
            ->with(['patient.user', 'schedule'])
            ->orderBy('appointment_date', 'desc')
            ->get();

        // Pass the logo path to the view
        $logoPath = public_path('img/app-logo.png');
        
        $pdf = PDF::loadView('exports.appointments-print', compact('appointments', 'logoPath'));
        $pdf->setPaper('a4');
        
        return $pdf->stream('my_appointments_' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Export patients list to Excel
     */
    public function exportPatients()
    {
        return Excel::download(new DoctorPatientsExport(), 'my_patients_' . now()->format('Y-m-d') . '.xlsx');
    }

}

<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Speciality;
use App\Models\Schedule;
use App\Models\EmergencyContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Exports\PatientAppointmentsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Stevebauman\Location\Facades\Location;
use Geocoder\Provider\OpenStreetMap\OpenStreetMap;
use Geocoder\Query\GeocodeQuery;
use Geocoder\Query\ReverseQuery;
use Geocoder\Provider\Nominatim\Nominatim;
use App\Exports\AppointmentsExport;

class PatientController extends Controller
{
    //XSS Attacks Functions

    private function isXssAttackDetected(array $originalInputs, array $sanitizedInputs): bool
    {
        foreach ($originalInputs as $index => $originalInput) {
            if ($originalInput !== $sanitizedInputs[$index]) {
                return true;
            }
        }
        return false;
    }

    // views Functions
    public function index()
    {
        $appointments = Auth::user()->patient->Appointments;
        return view('panels.patient.index')->with(compact('appointments'));
    }

    public function doctors()
    {
        $specialities = Speciality::all();
        $doctors = Doctor::orderByDesc('avg_rating')->get();
        return view('panels.patient.doctors')->with(compact('doctors'))->with(compact('specialities'));
    }
    public function appointment(Request $request, $id)
    {
        $doctor = Doctor::find($id);
        $schedule = $doctor->schedules;
        return view('panels.patient.appointment')->with(compact('schedule'))->with(compact('doctor'));
    }
    public function patient_appointments($id)
    {
        $appointments = Appointment::where('patient_id', $id)->get();
        return view('panels.patient.my_appointments')->with(compact('appointments'));
    }

    public function appointment_detail($id)
    {
        $appointment = Appointment::find($id);
        return view('panels.patient.CRUD.my_appointment-view')->with(compact('appointment'));
    }

    //Operation Functions

    public function getTips()
    {
        try {
            // Increase timeout to 60 seconds and add retry mechanism
            $response = Http::timeout(60)->retry(3, 5000)->get('https://newsapi.org/v2/top-headlines', [
                'apiKey' => '9d4f0f76580e49e7b654623b3837ddd3',
                'country' => 'us',
                'category' => 'health',
                'pageSize' => 50,
            ]);

            if ($response->successful()) {
                $healthTips = $response->json();
                $healthTips = collect($healthTips['articles'])->map(function ($article) {
                    if (!isset($article['urlToImage']) || empty($article['urlToImage'])) {
                        $article['urlToImage'] = 'https://via.placeholder.com/150'; // Default image
                    }
                    return $article;
                });

                return view('panels.patient.health_tips', compact('healthTips'));
            } else {
                // Log the error response
                Log::error('News API Error: ' . $response->status() . ' - ' . $response->body());
                return $this->getFallbackHealthTips();
            }
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            // Handle timeout or connection errors specifically
            Log::error('News API Connection Error: ' . $e->getMessage());
            return $this->getFallbackHealthTips();
        } catch (\Exception $e) {
            // Handle any other unexpected errors
            Log::error('News API Exception: ' . $e->getMessage());
            return $this->getFallbackHealthTips();
        }
    }

    /**
     * Provides fallback health tips when the API request fails
     *
     * @return \Illuminate\View\View
     */
    private function getFallbackHealthTips()
    {
        // Create some local fallback health articles
        $fallbackArticles = [
            [
                'title' => 'Importance of Regular Exercise',
                'description' => 'Regular physical activity is one of the most important things you can do for your health.',
                'url' => '#',
                'urlToImage' => 'https://via.placeholder.com/150',
                'publishedAt' => now()->format('Y-m-d\TH:i:s\Z'),
                'source' => ['name' => 'QuickCare Health Tips']
            ],
            [
                'title' => 'Balanced Diet for Optimal Health',
                'description' => 'A balanced diet provides your body with the nutrients it needs to function properly.',
                'url' => '#',
                'urlToImage' => 'https://via.placeholder.com/150',
                'publishedAt' => now()->format('Y-m-d\TH:i:s\Z'),
                'source' => ['name' => 'QuickCare Health Tips']
            ],
            [
                'title' => 'The Importance of Sleep',
                'description' => 'Quality sleep is essential for overall health and well-being.',
                'url' => '#',
                'urlToImage' => 'https://via.placeholder.com/150',
                'publishedAt' => now()->format('Y-m-d\TH:i:s\Z'),
                'source' => ['name' => 'QuickCare Health Tips']
            ],
            [
                'title' => 'Staying Hydrated',
                'description' => 'Water is essential for many bodily functions, including regulating body temperature and removing waste.',
                'url' => '#',
                'urlToImage' => 'https://via.placeholder.com/150',
                'publishedAt' => now()->format('Y-m-d\TH:i:s\Z'),
                'source' => ['name' => 'QuickCare Health Tips']
            ],
            [
                'title' => 'Mental Health Awareness',
                'description' => 'Taking care of your mental health is just as important as physical health.',
                'url' => '#',
                'urlToImage' => 'https://via.placeholder.com/150',
                'publishedAt' => now()->format('Y-m-d\TH:i:s\Z'),
                'source' => ['name' => 'QuickCare Health Tips']
            ],
        ];

        $healthTips = collect(['articles' => $fallbackArticles]);
        
        // Flash a message to inform the user about the API issue
        session()->flash('warning', 'We are currently having difficulties fetching the latest health articles. Showing local health tips instead.');
        
        return view('panels.patient.health_tips', compact('healthTips'));
    }

    public function cancel_appointment($id)
    {
        $appointment = Appointment::find($id);
        if ($appointment) {
            $appointment->update(['status' => 'Cancelled']);
            $appointment->save();
            return redirect()->back()->with('success', 'Appointment Canceled !!');
        } else {
            return redirect()->back()->with('error', 'Appointment Not Found !!');
        }
    }
    public function allDoctors()
    {
        $doctors = Doctor::with(['user.address', 'speciality'])
            ->orderByDesc('avg_rating')
            ->get();
        return response()->json($doctors);
    }


    public function filterDoctors(Request $request)
    {
        // Validate and sanitize inputs
        $specialityId = $request->input('speciality_id', null);
        $city = $request->input('city', null);
        $name = $request->input('name', null); // Get the name parameter

        // Prepare the query
        $doctorsQuery = Doctor::with('user.address', 'speciality');

        // Apply filters
        if ($specialityId) {
            $doctorsQuery->whereHas('speciality', function ($query) use ($specialityId) {
                $query->where('id', $specialityId);
            });
        }

        if ($city) {
            // Sanitize the city input to remove potentially harmful characters
            $sanitizedCity = filter_var($city, FILTER_SANITIZE_STRING);

            // Apply the city filter using parameterized query to prevent SQL injection
            $doctorsQuery->whereHas('user.address', function ($query) use ($sanitizedCity) {
                $query->where('ville', 'like', "%{$sanitizedCity}%");
            });
        }

        if ($name) { // Apply name filter if it's not empty
            $doctorsQuery->whereHas('user', function ($query) use ($name) {
                $query->where('name', 'like', "%{$name}%");
            });
        }

        // Execute the query
        $doctors = $doctorsQuery->get();

        // Return the result
        return response()->json($doctors);
    }

    public function getAvailableHours(Request $request, $id)
    {
        // Retrieve the date from the request
        $date = $request->input('date');

        // Convert the date to day of the week (e.g., Monday, Tuesday)
        $dayOfWeek = Carbon::parse($date)->format('l');

        // Retrieve available hours for the selected date and day of the week
        $query = Schedule::where('doctor_id', $id)
            ->where('status', 'false')
            ->where(function($query) use ($date, $dayOfWeek) {
                // Either match the day of week with no specific date
                $query->where(function($q) use ($dayOfWeek) {
                    $q->where('day', $dayOfWeek)
                      ->whereNull('specific_date');
                })
                // OR match the specific date exactly
                ->orWhere('specific_date', $date);
            });
        
        $availableHours = $query->get();
        
        // Format the response to include date information
        $formattedHours = [];
        foreach ($availableHours as $hour) {
            $slotDate = $hour->specific_date ? Carbon::parse($hour->specific_date) : Carbon::parse($date);
            $formattedDay = $slotDate->format('l'); // Monday, Tuesday, etc.
            $formattedDate = $slotDate->format('M d, Y'); // Apr 21, 2025
            
            $formattedHours[] = [
                'id' => $hour->id,
                'start' => $hour->start,
                'day' => $formattedDay,
                'date' => $formattedDate,
                'display' => "$formattedDay $formattedDate"
            ];
        }

        // Return the available hours as JSON response
        return response()->json($formattedHours);
    }

    public function getAppointments(Request $request, $id)
    {
        // Retrieve the date from the request
        $date = $request->input('date');

        // Retrieve appointments for the specified doctor and date
        $appointments = Appointment::where('doctor_id', $id)
            ->whereDate('appointment_date', $date)
            ->get();

        // Return appointments as JSON response
        return response()->json($appointments);
    }
    public function downloadPDF_Appointment($id)
    {
        $appointment = Appointment::findOrFail($id);

        $pdf = PDF::loadView('pdf.appointment-details', compact('appointment'));

        return $pdf->download('appointment_details.pdf');
    }

    /**
     * Export appointments to Excel
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function exportAppointments(Request $request)
    {
        try {
            // Vérifier si l'utilisateur a des rendez-vous
            $appointmentsCount = Auth::user()->patient->appointments()->count();
            
            if ($appointmentsCount === 0) {
                return back()->with('error', 'Aucun rendez-vous trouvé à exporter.');
            }

            // Générer un nom de fichier avec la date actuelle
            $filename = 'Liste_Rendez_Vous_' . date('Y-m-d');
            
            // Utiliser la classe d'exportation pour générer un fichier Excel professionnel
            return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\PatientAppointmentsExport(), $filename . '.xlsx');
            
        } catch (\Exception $e) {
            // Journaliser l'erreur
            \Illuminate\Support\Facades\Log::error('Export error: ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue lors de l\'exportation des rendez-vous: ' . $e->getMessage());
        }
    }
    
    /**
     * Show the emergency contacts form page
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function showEmergencyContactsForm()
    {
        // Create default emergency contacts for initial page load
        $emergencyContacts = $this->getMoroccanEmergencyContacts();
        return view('panels.patient.emergency_contacts', [
            'emergencyContacts' => $emergencyContacts,
            'searchType' => 'nearby'
        ]);
    }
    
    /**
     * Process emergency contacts search and return results
     * 
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function showEmergencyContacts(Request $request)
    {
        // Get search parameters
        $searchType = $request->input('search_type', 'nearby');
        $location = $request->input('location');
        $specialty = $request->input('specialty');
        
        // Get full list of emergency contacts
        $allContacts = $this->getMoroccanEmergencyContacts();
        
        // Filter contacts based on search criteria
        if ($searchType === 'nearby') {
            // For demo purposes, just return all contacts
            $emergencyContacts = $allContacts;
        } elseif ($searchType === 'specialty' && $specialty) {
            // Filter by specialty
            $emergencyContacts = $allContacts->filter(function($contact) use ($specialty) {
                return stripos($contact->speciality->name, $specialty) !== false;
            });
        } elseif ($searchType === 'location' && $location) {
            // Filter by location
            $emergencyContacts = $allContacts->filter(function($contact) use ($location) {
                return stripos($contact->user->address->ville, $location) !== false;
            });
        } else {
            // Default to all contacts
            $emergencyContacts = $allContacts;
        }
        
        return view('panels.patient.emergency_contacts', compact('emergencyContacts', 'searchType', 'location', 'specialty'));
    }
    
    /**
     * Get comprehensive list of emergency contacts for major Moroccan cities
     * 
     * @return \Illuminate\Support\Collection
     */
    private function getMoroccanEmergencyContacts()
    {
        return collect([
            // CASABLANCA
            $this->createSampleEmergencyContact(
                'Dr. Mohammed Alaoui', 
                'Emergency Medicine', 
                'Casablanca', 
                'Avenue Hassan II, Centre Hospitalier Universitaire Ibn Rochd', 
                'alaoui.med@example.com', 
                '+212 6 61 23 45 67'
            ),
            $this->createSampleEmergencyContact(
                'Dr. Sara Bennani', 
                'Trauma Surgery', 
                'Casablanca', 
                'Boulevard Massira Al Khadra, Hôpital Cheikh Khalifa', 
                'sara.bennani@example.com', 
                '+212 6 22 33 44 55'
            ),
            $this->createSampleEmergencyContact(
                'Dr. Hassan El Moukhtari', 
                'Emergency Medicine', 
                'Casablanca', 
                'Quartier Racine, Clinique Privée Dar Salam', 
                'h.elmoukhtari@example.com', 
                '+212 6 69 01 23 45'
            ),
            $this->createSampleEmergencyContact(
                'Dr. Nadia Sahraoui', 
                'Cardiac Emergency', 
                'Casablanca', 
                'Route d\'El Jadida, Clinique Internationale du Parc', 
                'n.sahraoui@example.com', 
                '+212 6 88 77 66 55'
            ),
            
            // TANGIER
            $this->createSampleEmergencyContact(
                'Dr. Amina Tahiri', 
                'Trauma Surgery', 
                'Tangier', 
                'Avenue Mohammed VI, Centre Hospitalier Mohammed V', 
                'amina.t@example.com', 
                '+212 6 64 56 78 90'
            ),
            $this->createSampleEmergencyContact(
                'Dr. Kamal Belhaj', 
                'Emergency Pediatrics', 
                'Tangier', 
                'Boulevard Moulay Rachid, Hôpital Italien', 
                'k.belhaj@example.com', 
                '+212 6 39 28 17 65'
            ),
            $this->createSampleEmergencyContact(
                'Dr. Yasmine Mansouri', 
                'Critical Care', 
                'Tangier', 
                'Rue Beethoven, Clinique Assalam', 
                'y.mansouri@example.com', 
                '+212 6 54 32 10 98'
            ),
            
            // FÈS
            $this->createSampleEmergencyContact(
                'Dr. Karim Idrissi', 
                'Critical Care', 
                'Fès', 
                'Rue Ibn Khaldoun, CHU Hassan II', 
                'k.idrissi@example.com', 
                '+212 6 65 67 89 01'
            ),
            $this->createSampleEmergencyContact(
                'Dr. Malika Zouaoui', 
                'Emergency Medicine', 
                'Fès', 
                'Avenue Hassan II, Hôpital Al Ghassani', 
                'm.zouaoui@example.com', 
                '+212 6 12 34 56 78'
            ),
            $this->createSampleEmergencyContact(
                'Dr. Rachid Tazi', 
                'Neurological Emergency', 
                'Fès', 
                'Route Sefrou, Clinique Atlas', 
                'r.tazi@example.com', 
                '+212 6 91 82 73 64'
            ),
            
            // MARRAKECH
            $this->createSampleEmergencyContact(
                'Dr. Youssef Mansouri', 
                'Pediatric Emergency', 
                'Marrakech', 
                'Boulevard Mohammed V, Hôpital Militaire Avicenne', 
                'y.mansouri@example.com', 
                '+212 6 63 45 67 89'
            ),
            $this->createSampleEmergencyContact(
                'Dr. Laila Benkirane', 
                'Emergency Medicine', 
                'Marrakech', 
                'Avenue Moulay Abdullah, CHU Mohammed VI', 
                'l.benkirane@example.com', 
                '+212 6 23 45 67 89'
            ),
            $this->createSampleEmergencyContact(
                'Dr. Omar Belkadi', 
                'Surgical Emergency', 
                'Marrakech', 
                'Route de Casablanca, Clinique Internationale', 
                'o.belkadi@example.com', 
                '+212 6 78 90 12 34'
            ),
            
            // SALÉ
            $this->createSampleEmergencyContact(
                'Dr. Fatima Zohra El Amrani', 
                'General Emergency', 
                'Salé', 
                'Avenue Mohammed V, Hôpital Moulay Abdellah', 
                'f.elamrani@example.com', 
                '+212 6 45 67 89 01'
            ),
            $this->createSampleEmergencyContact(
                'Dr. Abdelkader Bouazza', 
                'Trauma Care', 
                'Salé', 
                'Boulevard Mohammed Zefzaf, Centre de Santé Bab Lamrissa', 
                'a.bouazza@example.com', 
                '+212 6 34 56 78 90'
            ),
            $this->createSampleEmergencyContact(
                'Dr. Hanane Bennouna', 
                'Pediatric Emergency', 
                'Salé', 
                'Hay Salam, Clinique Assafa', 
                'h.bennouna@example.com', 
                '+212 6 56 78 90 12'
            ),
            
            // MEKNÈS
            $this->createSampleEmergencyContact(
                'Dr. Naima Tazi', 
                'Obstetric Emergency', 
                'Meknès', 
                'Avenue des FAR, Hôpital Mohamed V', 
                'n.tazi@example.com', 
                '+212 6 68 90 12 34'
            ),
            $this->createSampleEmergencyContact(
                'Dr. Hamid Chaoui', 
                'Emergency Medicine', 
                'Meknès', 
                'Boulevard Allal Ben Abdellah, Hôpital Al Amal', 
                'h.chaoui@example.com', 
                '+212 6 89 01 23 45'
            ),
            $this->createSampleEmergencyContact(
                'Dr. Samira Alami', 
                'Cardiac Emergency', 
                'Meknès', 
                'Hay Riad, Clinique Jardin', 
                's.alami@example.com', 
                '+212 6 90 12 34 56'
            ),
            
            // RABAT
            $this->createSampleEmergencyContact(
                'Dr. Fatima Benali', 
                'Cardiology Emergency', 
                'Rabat', 
                'Rue Abou Faris Al-Marini, Hôpital Cheikh Zaïd', 
                'fatima.benali@example.com', 
                '+212 6 62 34 56 78'
            ),
            $this->createSampleEmergencyContact(
                'Dr. Rachida Bennis', 
                'Pediatric Emergency', 
                'Rabat', 
                'Hay Riad, Clinique Internationale de Rabat', 
                'r.bennis@example.com', 
                '+212 6 70 12 34 56'
            ),
            $this->createSampleEmergencyContact(
                'Dr. Ahmed Benjelloun', 
                'Emergency Medicine', 
                'Rabat', 
                'Avenue Ibn Sina, Hôpital Militaire Mohammed V', 
                'a.benjelloun@example.com', 
                '+212 6 01 23 45 67'
            ),
            $this->createSampleEmergencyContact(
                'Dr. Meryem Lahlou', 
                'Neurological Emergency', 
                'Rabat', 
                'Avenue Al Araar, Hôpital des Spécialités', 
                'm.lahlou@example.com', 
                '+212 6 12 34 56 78'
            ),
            
            // Additional specialists
            $this->createSampleEmergencyContact(
                'Dr. Samir El Ouarzazi', 
                'Emergency Medicine', 
                'Oujda', 
                'Boulevard Zerktouni, CHU Mohammed VI', 
                's.elouarzazi@example.com', 
                '+212 6 67 89 01 23'
            ),
            $this->createSampleEmergencyContact(
                'Dr. Omar Benjelloun', 
                'Rural Emergency Care', 
                'Chefchaouen', 
                'Centre de Santé Provincial', 
                'o.benjelloun@example.com', 
                '+212 6 71 23 45 67'
            ),
            $this->createSampleEmergencyContact(
                'Dr. Salma Ziani', 
                'General Emergency', 
                'Essaouira', 
                'Hôpital Provincial Sidi Mohammed Ben Abdellah', 
                's.ziani@example.com', 
                '+212 6 72 34 56 78'
            ),
            $this->createSampleEmergencyContact(
                'Dr. Leila Chraibi', 
                'Neurology Emergency', 
                'Agadir', 
                'Boulevard Hassan I, Hôpital Hassan II', 
                'l.chraibi@example.com', 
                '+212 6 66 78 90 12'
            )
        ]);
    }

    /**
     * Create a sample emergency contact with the given details
     * 
     * @param string $name
     * @param string $specialty
     * @param string $city
     * @param string $address
     * @param string $email
     * @param string $phone
     * @return \stdClass
     */
    private function createSampleEmergencyContact($name, $specialty, $city, $address, $email, $phone)
    {
        $contact = new \stdClass();
        $contact->user = new \stdClass();
        $contact->user->name = $name;
        $contact->user->email = $email;
        $contact->user->phone = $phone;
        
        $contact->user->address = new \stdClass();
        $contact->user->address->ville = $city;
        $contact->user->address->adresse = $address;
        
        $contact->speciality = new \stdClass();
        $contact->speciality->name = $specialty;
        
        return $contact;
    }

    /**
     * Print appointments list
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function printAppointments(Request $request)
    {
        try {
            // Get the user's appointments
            $appointments = Auth::user()->patient->appointments()->with(['doctor.user', 'schedule'])->get();
            
            if ($appointments->isEmpty()) {
                return back()->with('error', 'Aucun rendez-vous trouvé à imprimer.');
            }
            
            // Return view specifically designed for printing
            return view('panels.patient.print_appointments', [
                'appointments' => $appointments,
                'patientName' => Auth::user()->name,
                'generatedDate' => Carbon::now()->format('d/m/Y H:i')
            ]);
            
        } catch (\Exception $e) {
            // Log the error
            \Illuminate\Support\Facades\Log::error('Print error: ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue lors de la préparation de l\'impression: ' . $e->getMessage());
        }
    }

    // CRUD Functions
    public function rateDoctor(Request $request, $doctorId, $patientId)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:255',
        ]);

        // Find the doctor and patient
        $doctor = Doctor::findOrFail($doctorId);
        $patient = Patient::findOrFail($patientId);

        // Check if a rating already exists for this doctor/patient combination
        $existingRating = Rating::where('doctor_id', $doctor->id)
            ->where('patient_id', $patient->id)
            ->first();

        if ($existingRating) {
            // Update existing rating
            $existingRating->rating = $validatedData['rating'];
            $existingRating->comment = $validatedData['comment'] ?? null;
            $existingRating->save();
            $rating = $existingRating;
        } else {
            // Create a new rating
            $rating = new Rating();
            $rating->doctor_id = $doctor->id;
            $rating->patient_id = $patient->id;
            $rating->rating = $validatedData['rating'];
            $rating->comment = $validatedData['comment'] ?? null; // If message is not provided, set it to null
            $rating->save();
        }

        // Update the average rating for the doctor
        $ratings = Rating::where('doctor_id', $doctor->id)->pluck('rating');
        $avgRating = $ratings->isEmpty() ? 0 : $ratings->avg();
        $doctor->avg_rating = $avgRating;
        $doctor->save();
        
        // Redirect back with success message
        return redirect()->back()->with('success', 'Rating submitted successfully');
    }
    
    public function myRatings()
    {
        $patient = Auth::user()->patient;
        $ratings = Rating::where('patient_id', $patient->id)
            ->with('doctor.user')
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Handle export requests
        if (request()->has('export') && request()->get('export') === 'csv') {
            return $this->exportRatingsCSV($ratings);
        }
            
        return view('panels.patient.my_ratings', compact('ratings'));
    }
    
    private function exportRatingsCSV($ratings)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="my_ratings_' . date('Y-m-d') . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($ratings) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Doctor', 'Rating', 'Comment', 'Date']);

            foreach ($ratings as $rating) {
                fputcsv($file, [
                    $rating->doctor->user->name,
                    $rating->rating,
                    $rating->comment ?? 'No comment',
                    $rating->created_at->format('Y-m-d')
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
    
    public function bookAppointment(Request $request, $D_ID, $P_ID)
    {
        $reason_for_appointment = $request->input('reason_for_appointment');
        $appointment_date = $request->input('appointment_date');
        $schedule_id = $request->input('appointment_time');

        // Validating form inputs
        $validator = Validator::make($request->all(), [
            'reason_for_appointment' => 'required|string|max:255',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|exists:schedules,id', // Ensure the selected schedule ID exists in the database
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Creating the appointment
        $appointment = Appointment::create([
            'reason' => $reason_for_appointment,
            'appointment_date' => $appointment_date,
            'schedule_id' => $schedule_id, // Storing the selected schedule ID
            'doctor_id' => $D_ID,
            'doctor_comment' => 'none',
            'patient_id' => $P_ID,
            'status' => 'Pending',
        ]);

        if ($appointment) {

            sendSupportEmail([
                'to' => $appointment->patient->user->email,
                'subject' => 'Appointment Confirmation',
                'content' => 'Your appointment has been successfully booked. Please find the details below:',
                'contactLink' => 'http://127.0.0.1:8000/patient/my/appointments/' . $appointment->patient->id,
                'contactText' => 'My Appointments',
                'phoneNumber' => '+1234567890',
            ]);

            return redirect()->back()->with('success', 'Appointment booked successfully');
        } else {
            return redirect()->back()->with('error', 'Appointment booking failed');
        }
    }
}

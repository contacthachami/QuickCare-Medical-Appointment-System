<?php

//Patient's routes

use App\Http\Controllers\PatientController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'patient'])->group(function () {

    //Doctor Routes
    Route::get('/patient/doctors', [PatientController::class, 'doctors'])->name('patiens.doctors');
    Route::post('/patient/doctor/rate/{D_id}/{P_id}', [PatientController::class, 'rateDoctor'])->name('patient.doctor.rate');
    Route::get('/patient/my-ratings', [PatientController::class, 'myRatings'])->name('patient.my.ratings');
});

<?php

//Patient's routes

use App\Http\Controllers\DoctorController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'doctor'])->group(function () {
    //Doctor Appointments Routes
    Route::get('/doctor/appointments', [DoctorController::class, 'appointments'])->name('doctor.appointments');
        Route::get('doctor/appointment/edit/{id}', [DoctorController::class, 'editAppointmentView'])->name('doctor.CRUD.appointment.edit');
        Route::put('doctor/appointment/edit/{id}', [DoctorController::class, 'updateAppointment'])->name('doctor.appointment.update');
        Route::get('/doctor/appointments/calendar', [DoctorController::class, 'getAppointmentsForCalendar'])->name('doctor.appointments.calendar');
        Route::get('/doctor/appointments/{id}', [DoctorController::class, 'getAppointmentDetails'])->name('doctor.appointments.details');
        Route::get('/doctor/appointment/index', [DoctorController::class, 'getAppointments'])->name('doctor.appointment.index');
        Route::get('doctor/appointment/{id}/details',[DoctorController::class , 'appointmentDetails'])
        ->name('doctor.CRUD.appointment.details');
        
        // Check-in and Check-out routes
        Route::post('doctor/appointment/{id}/check-in', [DoctorController::class, 'checkIn'])->name('doctor.appointment.check-in');
        Route::post('doctor/appointment/{id}/check-out', [DoctorController::class, 'checkOut'])->name('doctor.appointment.check-out');
        
        // Travel tracking routes
        Route::get('/doctor/travel-tracking', [DoctorController::class, 'travelTracking'])->name('doctor.travel-tracking');
        Route::get('/doctor/travel/export/{format}', [DoctorController::class, 'exportTravelRecords'])->name('doctor.travel.export');
        
        // Add specific named routes for different export formats
        Route::get('/doctor/travel/export-excel', [DoctorController::class, 'exportTravelRecords'])->name('doctor.travel.export.excel')->defaults('format', 'excel');
        Route::get('/doctor/travel/export-csv', [DoctorController::class, 'exportTravelRecords'])->name('doctor.travel.export.csv')->defaults('format', 'csv');
        Route::get('/doctor/travel/export-pdf', [DoctorController::class, 'exportTravelRecords'])->name('doctor.travel.export.pdf')->defaults('format', 'pdf');
        
        // Calendar schedules routes
        Route::get('/doctor/schedules', [DoctorController::class, 'getAllSchedulesForCalendar'])->name('doctor.schedules.index');

});

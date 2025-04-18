<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->group(function () {
    // Admin Dashboard
    Route::get('/admin', [AdminController::class, 'index'])->name('admin_dashboard');
    
    // Doctor Travel Times Report
    Route::get('/admin/doctor-travel-times', [AdminController::class, 'doctorTravelTimes'])->name('admin.doctor.travel-times');
});

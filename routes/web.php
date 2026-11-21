<?php

use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

// PROJECT
// read
Route::view('projects', 'project.index')->name('projects');
// create
Route::get('projects/create-new', [ProjectController::class, 'create'])->name('project.create');
Route::post('projects/store', [ProjectController::class, 'store'])->name('project.store');


// update
// delete

// TASK

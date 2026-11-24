<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

//Route::view('/', 'welcome')->name('home');

// PROJECT
//Route::livewire('/project/create', 'pages::project.create')->name('project.create');
//Route::livewire('/', 'pages::project.index')->name('project.index');
// read
Route::get('/', [ProjectController::class, 'index'])->name('project.index');
//Route::get('/project/{project}', [ProjectController::class, 'show'])->name('project.show');
Route::livewire('/projects/{project}/show', 'pages::project.show')->name('project.show');
Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('project.edit');
// create
Route::get('/projects/create', [ProjectController::class, 'create'])->name('project.create');
Route::post('/projects', [ProjectController::class, 'store'])->name('project.store');
// update
Route::patch('/projects/{project}', [ProjectController::class, 'update'])->name('project.update');
// delete
Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('project.destroy');

// TASK
Route::get('/tasks', [TaskController::class, 'index'])->name('task.index');
Route::post('/tasks', [TaskController::class, 'store'])->name('task.store');
Route::patch('/tasks{task}', [TaskController::class, 'update'])->name('task.update');
Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('task.destroy');

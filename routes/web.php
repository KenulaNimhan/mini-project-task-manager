<?php

use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProjectController::class, 'index'])->name('project.index');
Route::get('/projects/create', [ProjectController::class, 'create'])->name('project.create');
Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('project.edit');

Route::post('/projects', [ProjectController::class, 'store'])->name('project.store');
Route::patch('/projects/{project}', [ProjectController::class, 'update'])->name('project.update');
Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('project.destroy');

Route::livewire('/projects/{project}/show', 'pages::project.show')->name('project.show');

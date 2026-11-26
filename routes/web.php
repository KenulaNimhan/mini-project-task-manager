<?php

use App\Livewire\CreateProject;
use App\Livewire\EditProject;
use App\Livewire\ProjectIndex;
use App\Livewire\ShowProject;
use Illuminate\Support\Facades\Route;

Route::livewire('/', ProjectIndex::class)->name('project.index');
Route::livewire('/projects/create', CreateProject::class)->name('project.create');
Route::livewire('/projects/{project}/edit', EditProject::class)->name('project.edit');
Route::livewire('/projects/{project}', ShowProject::class)->name('project.show');

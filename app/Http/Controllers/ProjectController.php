<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::all();

        return view('pages.project.index', ['projects' => $projects]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.project.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validate project
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);
        // create a new project in database
        Project::create($validated);

        return redirect(route('project.index'));

    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        return view('pages.project.edit', ['project' => $project]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);
        $project->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect(route('project.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $incomplete_tasks = $project->tasks()
            ->where('is_completed', false)
            ->get();

        if ($incomplete_tasks->count() == 0) {
            $project->delete();
            return redirect(route('project.index'))->with('success', 'Project successfully deleted');
        } else {
            return back()->with('error', 'Cannot delete a project with incomplete tasks');
        }
    }
}

<?php

namespace App\Livewire;

use App\Models\Project;
use Livewire\Component;

class ProjectIndex extends Component
{
    public function deleteProject(int $projectId): void
    {
        $project = Project::findOrFail($projectId);

        if ($project->tasks()->where('is_completed', false)->exists()) {
            session()->flash('error', 'Cannot delete a project with incomplete tasks');
            $this->redirectRoute('project.index');

            return;
        }

        $project->delete();
        session()->flash('success', 'Project successfully deleted');
        $this->redirectRoute('project.index');
    }

    public function render()
    {
        return view('livewire.project-index', [
            'projects' => Project::with('tasks')->get(),
        ]);
    }
}

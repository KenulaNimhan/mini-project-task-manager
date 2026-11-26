<?php

namespace App\Livewire;

use App\Models\Project;
use Livewire\Component;

class EditProject extends Component
{
    public Project $project;

    public string $name = '';

    public string $description = '';

    public function mount(Project $project): void
    {
        $this->project = $project;
        $this->name = $project->name;
        $this->description = $project->description ?? '';
    }

    public function update(): void
    {
        $validated = $this->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $this->project->update($validated);

        $this->redirectRoute('project.index');
    }

    public function render()
    {
        return view('livewire.edit-project');
    }
}

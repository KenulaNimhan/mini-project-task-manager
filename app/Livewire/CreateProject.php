<?php

namespace App\Livewire;

use App\Models\Project;
use Livewire\Component;

class CreateProject extends Component
{
    public string $name = '';

    public string $description = '';

    public function store(): void
    {
        $validated = $this->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);

        Project::create($validated);

        $this->redirectRoute('project.index');
    }

    public function render()
    {
        return view('livewire.create-project');
    }
}

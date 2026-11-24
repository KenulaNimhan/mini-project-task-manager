<?php

use App\Models\Task;
use Livewire\Component;

new class extends Component
{
    public string $description = '';
    public ?int $projectId = null;

    public function createTask()
    {
        $validated = $this->validate([
            'description' => 'required|string|max:50'
        ]);

        Task::query()->create([
            'description' => $this->description,
            'project_id' => $this->projectId,
        ]);

        $this->reset('description');
    }
};
?>

<div>
    <form wire:submit="createTask" class="w-full mt-10 mb-10">
        <input type="text" placeholder="Add new task" wire:model="description"
               class="w-full pt-10 pb-10 pl-2
               border-2 border-slate-500 rounded-xl
               "/>
        <button type="submit" class="submitBtn mt-2 ">Add task</button>
    </form>
</div>

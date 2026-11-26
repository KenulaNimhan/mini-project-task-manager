<?php

namespace App\Livewire;

use App\Models\Project;
use App\Models\Task;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ShowProject extends Component
{
    public Project $project;

    public string $newTaskTitle = '';

    public ?int $editingTaskId = null;

    public string $editingTaskTitle = '';

    #[Computed]
    public function taskStats(): array
    {
        $totalTasks = $this->project->tasks()->count();
        $completedTasks = $this->project->tasks()
            ->where('is_completed', true)
            ->count();

        return [
            'total' => $totalTasks,
            'completed' => $completedTasks,
            'percentage' => $totalTasks > 0
                ? (int) round(($completedTasks / $totalTasks) * 100)
                : 0,
        ];
    }

    public function addTask(): void
    {
        $validated = $this->validate([
            'newTaskTitle' => 'required|string|max:255',
        ]);

        Task::create([
            'title' => $validated['newTaskTitle'],
            'project_id' => $this->project->id,
            'is_completed' => false,
        ]);

        $this->reset('newTaskTitle');
        $this->project->refresh();
        $this->project->load('tasks');
    }

    public function toggleTask(int $taskId): void
    {
        $task = Task::where('id', $taskId)
            ->where('project_id', $this->project->id)
            ->firstOrFail();

        $task->update([
            'is_completed' => ! $task->is_completed,
        ]);

        $this->project->refresh();
        $this->project->load('tasks');
    }

    public function deleteTask(int $taskId): void
    {
        Task::where('id', $taskId)
            ->where('project_id', $this->project->id)
            ->firstOrFail()
            ->delete();

        $this->project->refresh();
        $this->project->load('tasks');
    }

    public function editTask(int $taskId): void
    {
        $task = Task::where('id', $taskId)
            ->where('project_id', $this->project->id)
            ->firstOrFail();

        $this->editingTaskId = $task->id;
        $this->editingTaskTitle = $task->title;
    }

    public function saveTask(int $taskId): void
    {
        $validated = $this->validate([
            'editingTaskTitle' => 'required|string|max:255',
        ]);

        Task::where('id', $taskId)
            ->where('project_id', $this->project->id)
            ->firstOrFail()
            ->update([
                'title' => $validated['editingTaskTitle'],
            ]);

        $this->cancelEditingTask();
        $this->project->refresh();
        $this->project->load('tasks');
    }

    public function cancelEditingTask(): void
    {
        $this->reset(['editingTaskId', 'editingTaskTitle']);
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.show-project');
    }
}

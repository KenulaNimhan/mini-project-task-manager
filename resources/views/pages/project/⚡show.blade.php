<?php

use App\Models\Project;
use App\Models\Task;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    public Project $project;
    public string $newTaskDescription = '';
    public ?int $editingTaskId = null;
    public string $editingTaskDescription = '';

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
            'newTaskDescription' => 'required|string|max:255',
        ]);

        Task::create([
            'description' => $validated['newTaskDescription'],
            'project_id' => $this->project->id,
            'is_completed' => false,
        ]);

        $this->reset('newTaskDescription');
        $this->project->refresh();
        $this->project->load('tasks');
    }

    public function toggleTask(int $taskId): void
    {
        $task = Task::where('id', $taskId)
            ->where('project_id', $this->project->id)
            ->firstOrFail();

        $task->update([
            'is_completed' => !$task->is_completed,
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
        $this->editingTaskDescription = $task->description;
    }

    public function saveTask(int $taskId): void
    {
        $validated = $this->validate([
            'editingTaskDescription' => 'required|string|max:255',
        ]);

        Task::where('id', $taskId)
            ->where('project_id', $this->project->id)
            ->firstOrFail()
            ->update([
                'description' => $validated['editingTaskDescription'],
            ]);

        $this->cancelEditingTask();
        $this->project->refresh();
        $this->project->load('tasks');
    }

    public function cancelEditingTask(): void
    {
        $this->reset(['editingTaskId', 'editingTaskDescription']);
        $this->resetValidation();
    }
};
?>

<div class="projectPage w-[80%] m-auto mt-[8em] max-w-250">
    <a href="{{ route('project.index') }}" type="button"
       class="mb-5 inline-flex items-center gap-1.5 whitespace-nowrap shrink-0 rounded-md py-1 text-sm font-medium text-slate-600 transition-colors duration-150 hover:text-orange-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-orange-600">
        <x-icons.back/>
        Back to Projects
    </a>
    <div class="rounded-lg border border-slate-200 bg-white p-6">
        <div class="flex flex-col justify-between gap-5 sm:flex-row sm:items-start">
            <div class="min-w-0 flex-1">
                <h1 id="project-detail-heading"
                    class="text-2xl font-bold tracking-tight text-slate-900 text-balance">
                    {{ $project->name }}
                </h1>
                <p class="mt-2 max-w-2xl text-sm leading-relaxed text-slate-600">
                    {{ $project->description }}
                </p>
            </div>
        </div>
        <div class="mt-5 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-4">
            <span class="text-sm font-medium text-slate-700 tabular-nums">
                {{ $this->taskStats['completed'] }}/{{ $this->taskStats['total'] }} Tasks Completed
            </span>
            <div class="h-1.5 w-full overflow-hidden rounded-full bg-orange-300/50 sm:w-48"
                 role="progressbar"
                 aria-label="Project task completion"
                 aria-valuemin="0"
                 aria-valuemax="100"
                 aria-valuenow="{{ $this->taskStats['percentage'] }}">
                <div class="h-full bg-orange-600 transition-all duration-300"
                     style="width: {{ $this->taskStats['percentage'] }}%;"></div>
            </div>
        </div>
    </div>

    <div class="mt-10 mb-10 overflow-hidden rounded-lg border border-slate-200 bg-white">
        <form wire:submit.prevent="addTask"
              class="border-b border-slate-200 bg-slate-50/50 p-4 sm:p-5">
            <label for="new-task-title" class="sr-only">Add a new task</label>
            <div class="flex flex-col gap-3 sm:flex-row">
                <input wire:model="newTaskDescription" id="new-task-title" placeholder="Add a new task..." aria-invalid="false"
                       class="h-10 flex-1 rounded-md border px-3.5 text-sm text-slate-900 placeholder:text-slate-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-orange-600 border-slate-300 bg-white hover:border-slate-400"
                       type="text" value="">
                <button type="submit"
                        class="inline-flex h-10 items-center justify-center gap-1.5 whitespace-nowrap shrink-0 rounded-md bg-orange-700 px-4 text-sm font-medium text-white transition-colors duration-150 hover:bg-orange-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-orange-600 focus-visible:ring-offset-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-plus h-4 w-4" aria-hidden="true">
                        <path d="M5 12h14"></path>
                        <path d="M12 5v14"></path>
                    </svg>
                    Add Task
                </button>
            </div>
            @error('newTaskDescription')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </form>
        @if($project->tasks->isEmpty())
            <div class="px-6 py-12 text-center">
                <x-icons.tasks/>
                <p class="text-sm font-medium text-slate-700">No tasks in this project yet</p>
                <p class="mt-1 text-sm text-slate-500">Enter a task title above to add your first task.</p>
            </div>
        @else
            @foreach($project->tasks()->latest('id')->get() as $task)
                <div wire:key="task-{{ $task->id }}" class="flex items-center justify-between gap-4 border-b border-slate-100 px-4 py-3 last:border-b-0">
                    @if($editingTaskId === $task->id)
                        <input type="text"
                               wire:model="editingTaskDescription"
                               wire:keydown.enter.prevent="saveTask({{ $task->id }})"
                               wire:keydown.escape.prevent="cancelEditingTask"
                               class="h-9 min-w-0 flex-1 rounded-md border border-slate-300 px-3 text-sm text-slate-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-orange-600"
                               autofocus>
                        <button type="button"
                                wire:click="saveTask({{ $task->id }})"
                                aria-label="Save task"
                                class="inline-flex h-8 items-center justify-center rounded-md bg-orange-700 px-3 text-sm font-medium text-white hover:bg-orange-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-orange-600">
                            Save
                        </button>
                        <button type="button"
                                wire:click="cancelEditingTask"
                                aria-label="Cancel task edit"
                                class="inline-flex h-8 items-center justify-center rounded-md border border-slate-300 px-3 text-sm font-medium text-slate-600 hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-orange-600">
                            Cancel
                        </button>
                    @else
                        <label class="flex min-w-0 flex-1 items-center gap-3">
                            <input type="checkbox"
                                   wire:change="toggleTask({{ $task->id }})"
                                   @checked($task->is_completed)
                                   class="h-4 w-4 shrink-0 rounded border-slate-300 accent-orange-600">
                            <span class="task-title min-w-0 line-clamp-2 text-sm {{ $task->is_completed ? 'task-completed' : '' }}">
                                {{ $task->description }}
                            </span>
                        </label>
                        <button type="button"
                                wire:click="editTask({{ $task->id }})"
                                aria-label="Edit task"
                                class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-md text-slate-400 transition-colors hover:bg-orange-50 hover:text-orange-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-orange-600">
                            <x-icons.edit/>
                        </button>
                        <button type="button"
                                wire:click="deleteTask({{ $task->id }})"
                                aria-label="Delete task"
                                class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-md text-slate-400 transition-colors hover:bg-red-50 hover:text-red-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-red-600">
                            <x-icons.delete/>
                        </button>
                    @endif
                </div>
            @endforeach
        @endif
    </div>
</div>


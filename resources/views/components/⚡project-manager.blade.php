<?php

use App\Models\Project;
use App\Models\Task;
use Livewire\Component;

new class extends Component
{
    public ?int $selectedProjectId = null;
    public bool $projectModalOpen = false;
    public ?int $editingProjectId = null;
    public string $projectName = '';
    public string $projectDescription = '';
    public ?int $pendingDeleteProjectId = null;
    public string $deleteError = '';
    public string $newTaskTitle = '';
    public ?int $editingTaskId = null;
    public string $editingTaskTitle = '';

    public function openCreateProject(): void
    {
        $this->editingProjectId = null;
        $this->projectName = '';
        $this->projectDescription = '';
        $this->resetValidation();
        $this->projectModalOpen = true;
    }

    public function openEditProject(int $projectId): void
    {
        $project = Project::findOrFail($projectId);
        $this->editingProjectId = $project->id;
        $this->projectName = $project->name;
        $this->projectDescription = $project->description ?? '';
        $this->resetValidation();
        $this->projectModalOpen = true;
    }

    public function closeProjectModal(): void
    {
        $this->projectModalOpen = false;
        $this->editingProjectId = null;
        $this->projectName = '';
        $this->projectDescription = '';
        $this->resetValidation();
    }

    public function saveProject(): void
    {
        $validated = $this->validate([
            'projectName' => ['required', 'string', 'max:255'],
            'projectDescription' => ['nullable', 'string', 'max:1000'],
        ]);

        $attributes = [
            'name' => trim($validated['projectName']),
            'description' => trim($validated['projectDescription'] ?? ''),
        ];

        if ($this->editingProjectId) {
            Project::findOrFail($this->editingProjectId)->update($attributes);
        } else {
            Project::create($attributes);
        }

        $this->closeProjectModal();
        $this->deleteError = '';
    }

    public function selectProject(int $projectId): void
    {
        $this->selectedProjectId = $projectId;
        $this->deleteError = '';
        $this->editingTaskId = null;
    }

    public function goBack(): void
    {
        $this->selectedProjectId = null;
        $this->deleteError = '';
        $this->editingTaskId = null;
    }

    public function attemptDeleteProject(int $projectId): void
    {
        $project = Project::withCount([
            'tasks as incomplete_tasks_count' => fn ($query) => $query->where('is_completed', false),
        ])->findOrFail($projectId);

        if ($project->incomplete_tasks_count > 0) {
            $this->deleteError = 'Cannot delete project "' . $project->name . '": There are uncompleted tasks (' . $project->incomplete_tasks_count . ' remaining).';
            $this->pendingDeleteProjectId = null;
            return;
        }

        $this->deleteError = '';
        $this->pendingDeleteProjectId = $projectId;
    }

    public function cancelDeleteProject(): void
    {
        $this->pendingDeleteProjectId = null;
    }

    public function confirmDeleteProject(): void
    {
        if (!$this->pendingDeleteProjectId) {
            return;
        }

        $project = Project::findOrFail($this->pendingDeleteProjectId);
        $hasIncompleteTasks = $project->tasks()->where('is_completed', false)->exists();

        if ($hasIncompleteTasks) {
            $this->deleteError = 'Cannot delete project: There are uncompleted tasks.';
            $this->pendingDeleteProjectId = null;
            return;
        }

        $project->tasks()->delete();
        $project->delete();
        if ($this->selectedProjectId === $project->id) {
            $this->selectedProjectId = null;
        }
        $this->pendingDeleteProjectId = null;
        $this->deleteError = '';
    }

    public function addTask(): void
    {
        $this->validate([
            'newTaskTitle' => ['required', 'string', 'max:255'],
        ], [
            'newTaskTitle.required' => 'Task title cannot be empty.',
        ]);

        if (!$this->selectedProjectId) {
            return;
        }

        Task::create([
            'project_id' => $this->selectedProjectId,
            'description' => trim($this->newTaskTitle),
            'is_completed' => false,
        ]);

        $this->newTaskTitle = '';
        $this->resetValidation();
    }

    public function toggleTask(int $taskId): void
    {
        $task = Task::findOrFail($taskId);
        $task->update(['is_completed' => !$task->is_completed]);
        $this->deleteError = '';
    }

    public function startEditingTask(int $taskId): void
    {
        $task = Task::findOrFail($taskId);
        $this->editingTaskId = $task->id;
        $this->editingTaskTitle = $task->description;
        $this->resetValidation();
    }

    public function cancelEditingTask(): void
    {
        $this->editingTaskId = null;
        $this->editingTaskTitle = '';
        $this->resetValidation();
    }

    public function saveEditingTask(): void
    {
        $this->validate([
            'editingTaskTitle' => ['required', 'string', 'max:255'],
        ], [
            'editingTaskTitle.required' => 'Task title cannot be empty.',
        ]);

        if ($this->editingTaskId) {
            Task::findOrFail($this->editingTaskId)->update([
                'description' => trim($this->editingTaskTitle),
            ]);
        }

        $this->cancelEditingTask();
    }

    public function deleteTask(int $taskId): void
    {
        Task::findOrFail($taskId)->delete();
        $this->deleteError = '';
    }

};
?>

@php
    $projects = Project::with('tasks')->latest()->get();
    $selectedProject = $selectedProjectId
        ? Project::with(['tasks' => fn ($query) => $query->latest()])->find($selectedProjectId)
        : null;
    $pendingDeleteProject = $pendingDeleteProjectId
        ? Project::withCount('tasks')->find($pendingDeleteProjectId)
        : null;
@endphp

<div>
    <header class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-5xl items-center justify-between gap-8 px-6 py-4">
            <div class="flex min-w-0 items-center gap-2.5 text-sm">
                <button type="button" wire:click="goBack" class="shrink-0 whitespace-nowrap rounded-sm text-base font-bold tracking-tight text-slate-900 hover:text-orange-700">
                    Project Tasks
                </button>
                @if($selectedProject)
                    <span class="text-slate-300">/</span>
                    <span class="truncate font-medium text-slate-600">{{ $selectedProject->name }}</span>
                @endif
            </div>
            <button type="button" wire:click="openCreateProject" class="shrink-0 rounded-md bg-orange-700 px-3.5 py-2 text-xs font-medium text-white hover:bg-orange-800">
                + New Project
            </button>
        </div>
    </header>

    <main class="mx-auto max-w-5xl px-6 py-8 sm:py-10">
        @if($deleteError)
            <div role="alert" class="mb-6 flex items-start justify-between gap-3 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                <p class="font-medium">{{ $deleteError }}</p>
                <button type="button" wire:click="$set('deleteError', '')" class="text-red-600" aria-label="Dismiss error">×</button>
            </div>
        @endif

        @if($selectedProject)
            <section class="space-y-6">
                <button type="button" wire:click="goBack" class="text-sm font-medium text-slate-600 hover:text-orange-700">← Back to Projects</button>

                <div class="rounded-lg border border-slate-200 bg-white p-6">
                    <div class="flex flex-col justify-between gap-5 sm:flex-row sm:items-start">
                        <div class="min-w-0 flex-1">
                            <h1 class="text-2xl font-bold tracking-tight text-slate-900">{{ $selectedProject->name }}</h1>
                            <p class="mt-2 max-w-2xl text-sm leading-relaxed text-slate-600">
                                {{ $selectedProject->description ?: 'No description provided for this project.' }}
                            </p>
                        </div>
                        <div class="flex shrink-0 items-center gap-2">
                            <button type="button" wire:click="openEditProject({{ $selectedProject->id }})" class="h-10 rounded-md border border-slate-300 bg-white px-3.5 text-sm font-medium text-slate-700 hover:bg-slate-50">
                                Edit Project
                            </button>
                            <button type="button" wire:click="attemptDeleteProject({{ $selectedProject->id }})" class="h-10 rounded-md border border-slate-300 bg-white px-3.5 text-sm font-medium text-red-600 hover:bg-red-50">
                                Delete
                            </button>
                        </div>
                    </div>
                    @php
                        $totalTasks = $selectedProject->tasks->count();
                        $completedTasks = $selectedProject->tasks->where('is_completed', true)->count();
                        $progress = $totalTasks ? round(($completedTasks / $totalTasks) * 100) : 0;
                    @endphp
                    <div class="mt-5 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-4">
                        <span class="text-sm font-medium text-slate-700 tabular-nums">{{ $completedTasks }}/{{ $totalTasks }} Tasks Completed</span>
                        <div class="h-1.5 w-full overflow-hidden rounded-full bg-slate-100 sm:w-48">
                            <div class="{{ $totalTasks && $completedTasks === $totalTasks ? 'bg-green-600' : 'bg-orange-600' }} h-full transition-all" style="width: {{ $progress }}%"></div>
                        </div>
                    </div>
                </div>

                <div class="overflow-hidden rounded-lg border border-slate-200 bg-white">
                    <form wire:submit="addTask" class="border-b border-slate-200 bg-slate-50/50 p-4 sm:p-5">
                        <div class="flex flex-col gap-3 sm:flex-row">
                            <input type="text" wire:model="newTaskTitle" placeholder="Add a new task..." class="h-10 flex-1 rounded-md border border-slate-300 bg-white px-3.5 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-orange-600">
                            <button type="submit" class="h-10 rounded-md bg-orange-700 px-4 text-sm font-medium text-white hover:bg-orange-800">+ Add Task</button>
                        </div>
                        @error('newTaskTitle') <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                    </form>

                    @if($selectedProject->tasks->isEmpty())
                        <div class="px-6 py-12 text-center">
                            <p class="text-sm font-medium text-slate-700">No tasks in this project yet</p>
                            <p class="mt-1 text-sm text-slate-500">Enter a task title above to add your first task.</p>
                        </div>
                    @else
                        <ul class="divide-y divide-slate-200">
                            @foreach($selectedProject->tasks as $task)
                                <li class="flex items-center justify-between gap-3 px-4 py-3 hover:bg-slate-50/70 sm:px-5">
                                    @if($editingTaskId === $task->id)
                                        <div class="flex flex-1 flex-wrap items-center gap-2">
                                            <input type="text" wire:model="editingTaskTitle" wire:keydown.enter.prevent="saveEditingTask" wire:keydown.escape="cancelEditingTask" autofocus class="h-9 min-w-0 flex-1 rounded-md border border-slate-300 px-3 text-sm">
                                            <button type="button" wire:click="saveEditingTask" class="h-9 rounded-md bg-orange-700 px-3 text-xs font-medium text-white">Save</button>
                                            <button type="button" wire:click="cancelEditingTask" class="h-9 rounded-md border border-slate-300 px-3 text-xs font-medium text-slate-700">Cancel</button>
                                        </div>
                                    @else
                                        <label class="flex min-w-0 flex-1 cursor-pointer items-center gap-3.5 py-1">
                                            <input type="checkbox" wire:change="toggleTask({{ $task->id }})" @checked($task->is_completed) class="h-4 w-4 rounded accent-orange-600">
                                            <span class="text-sm sm:text-[15px] {{ $task->is_completed ? 'text-slate-400 line-through' : 'text-slate-800' }}">{{ $task->description }}</span>
                                        </label>
                                        <div class="flex shrink-0 items-center gap-1">
                                            <button type="button" wire:click="startEditingTask({{ $task->id }})" class="h-9 w-9 rounded-md text-slate-500 hover:bg-slate-100" aria-label="Edit task">Edit</button>
                                            <button type="button" wire:click="deleteTask({{ $task->id }})" class="h-9 w-9 rounded-md text-slate-500 hover:bg-red-50 hover:text-red-600" aria-label="Delete task">×</button>
                                        </div>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                        @error('editingTaskTitle') <p class="px-5 pb-3 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                    @endif
                </div>
            </section>
        @else
            <section class="space-y-6">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">Projects</h1>
                    <p class="mt-1 text-sm text-slate-600">{{ $projects->count() }} {{ $projects->count() === 1 ? 'project' : 'projects' }} total</p>
                </div>

                @if($projects->isEmpty())
                    <div class="flex flex-col items-center justify-center rounded-lg border border-slate-200 bg-white px-6 py-16 text-center">
                        <h2 class="text-base font-semibold text-slate-900">No projects yet</h2>
                        <p class="mt-1 text-sm text-slate-600">Create your first project to organize and track its tasks.</p>
                        <button type="button" wire:click="openCreateProject" class="mt-6 rounded-md bg-orange-700 px-4 py-2 text-sm font-medium text-white hover:bg-orange-800">Create First Project</button>
                    </div>
                @else
                    <div class="overflow-hidden rounded-lg border border-slate-200 bg-white">
                        <ul class="divide-y divide-slate-200">
                            @foreach($projects as $project)
                                @php
                                    $total = $project->tasks->count();
                                    $completed = $project->tasks->where('is_completed', true)->count();
                                    $progress = $total ? round(($completed / $total) * 100) : 0;
                                @endphp
                                <li class="group flex flex-col justify-between gap-4 px-5 py-4 hover:bg-slate-50/80 sm:flex-row sm:items-center sm:px-6">
                                    <button type="button" wire:click="selectProject({{ $project->id }})" class="flex min-w-0 flex-1 flex-col text-left">
                                        <span class="text-base font-semibold text-slate-900 group-hover:text-orange-700">{{ $project->name }} →</span>
                                        <span class="mt-1 line-clamp-2 text-sm text-slate-600">{{ $project->description ?: 'No description' }}</span>
                                    </button>
                                    <div class="flex shrink-0 items-center justify-between gap-6 sm:justify-end">
                                        <div class="min-w-[148px]">
                                            <div class="text-sm font-medium text-slate-700 tabular-nums">{{ $completed }}/{{ $total }} Tasks Completed</div>
                                            <div class="mt-1.5 h-1.5 overflow-hidden rounded-full bg-slate-100">
                                                <div class="{{ $total && $completed === $total ? 'bg-green-600' : 'bg-orange-600' }} h-full" style="width: {{ $progress }}%"></div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <button type="button" wire:click="openEditProject({{ $project->id }})" class="h-10 rounded-md px-2.5 text-sm font-medium text-slate-600 hover:bg-slate-100">Edit</button>
                                            <button type="button" wire:click="attemptDeleteProject({{ $project->id }})" class="h-10 rounded-md px-2.5 text-sm font-medium text-slate-600 hover:bg-red-50 hover:text-red-700">Delete</button>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </section>
        @endif
    </main>

    @if($projectModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 p-4" role="dialog" aria-modal="true">
            <div class="w-full max-w-lg rounded-lg border border-slate-200 bg-white shadow-lg">
                <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
                    <h2 class="text-base font-semibold text-slate-900">{{ $editingProjectId ? 'Edit Project' : 'New Project' }}</h2>
                    <button type="button" wire:click="closeProjectModal" class="h-8 w-8 rounded-md text-slate-500 hover:bg-slate-100">×</button>
                </div>
                <form wire:submit="saveProject" class="space-y-5 p-6">
                    <div>
                        <label for="project-name" class="block text-sm font-medium text-slate-700">Project Name *</label>
                        <input id="project-name" type="text" wire:model="projectName" placeholder="e.g. Website Redesign" class="mt-1.5 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                        @error('projectName') <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="project-description" class="block text-sm font-medium text-slate-700">Description</label>
                        <textarea id="project-description" rows="3" wire:model="projectDescription" placeholder="Briefly describe the goal or scope of this project..." class="mt-1.5 block w-full resize-none rounded-md border border-slate-300 px-3 py-2 text-sm"></textarea>
                    </div>
                    <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-4">
                        <button type="button" wire:click="closeProjectModal" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700">Cancel</button>
                        <button type="submit" class="rounded-md bg-orange-700 px-4 py-2 text-sm font-medium text-white">{{ $editingProjectId ? 'Save Changes' : 'Create Project' }}</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if($pendingDeleteProject)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 p-4" role="dialog" aria-modal="true">
            <div class="w-full max-w-md rounded-lg border border-slate-200 bg-white shadow-lg">
                <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
                    <h2 class="text-base font-semibold text-slate-900">Delete Project</h2>
                    <button type="button" wire:click="cancelDeleteProject" class="h-8 w-8 rounded-md text-slate-500 hover:bg-slate-100">×</button>
                </div>
                <div class="p-6">
                    <p class="text-sm leading-relaxed text-slate-600">
                        Are you sure you want to delete <span class="font-semibold text-slate-900">{{ $pendingDeleteProject->name }}</span>?
                        All {{ $pendingDeleteProject->tasks_count }} completed {{ $pendingDeleteProject->tasks_count === 1 ? 'task' : 'tasks' }} in this project will also be permanently removed.
                    </p>
                    <div class="mt-6 flex items-center justify-end gap-3">
                        <button type="button" wire:click="cancelDeleteProject" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700">Cancel</button>
                        <button type="button" wire:click="confirmDeleteProject" class="rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white">Delete Project</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
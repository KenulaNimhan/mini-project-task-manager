<?php

use App\Models\Project;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    public bool $createModal = false;
    public bool $editModal = false;
    public bool $deleteConfirmation = false;
    public string $name = '';
    public string $description = '';
    public ?int $editingId = null;
    public ?int $deletingId = null;

    public string $nameOfProjectToDelete = '';

    #[Computed]
    public function projects()
    {
        return Project::all();
    }

    public function create()
    {
        $validated = $this->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);

        Project::create($validated);

        return $this->redirect(route('project.index'));
    }

    public function closeModal()
    {
        $this->reset([
            'createModal',
            'editModal',
            'deleteConfirmation',
            'name',
            'description',
            'editingId',
            'deletingId'
        ]);
        $this->resetValidation();
    }

    public function editForm(int $id)
    {
        $project = Project::findOrFail($id);

        $this->editingId = $project->id;
        $this->name = $project->name;
        $this->description = $project->description;
        $this->editModal = true;
    }

    public function edit()
    {
        $validate = $this->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $project = Project::find($this->editingId);
        $project->update([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        return $this->redirect(route('project.index'));
    }

    public function openDeleteConfirmationModal(int $id)
    {
        $projectToDelete = Project::findOrFail($id);
        $this->deletingId = $projectToDelete->id;
        $this->nameOfProjectToDelete = $projectToDelete->name;
        $this->deleteConfirmation = true;
    }

    public function delete()
    {
        Project::destroy($this->deletingId);
        $this->closeModal();
        return $this->redirect(route('project.index'));
    }
};
?>

<div>

    @if($createModal)
        <x-modal title="New Project" submit-text="Create Project" on-submit="create"/>
    @elseif($editModal)
        <x-modal title="Edit Project" submit-text="Save Edit" on-submit="edit"/>
    @elseif($deleteConfirmation)
        <x-confirmation project-name="{{ $nameOfProjectToDelete }}"/>
    @endif

    <div class="projectSection">
        <header>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Projects</h1>
                <span class="mt-1 text-sm text-slate-600">{{ $this->projects->count() }} projects total</span>
            </div>
            <button type="button" wire:click="$set('createModal', true)" class="submitBtn">+ New Project</button>
        </header>
        <section class="flex flex-col justify-center rounded-lg border border-slate-200 bg-white mt-5">
            @if($this->projects->isEmpty())
                <div class="px-6 py-16 items-center text-center">
                    <div
                        class="m-auto flex h-11 w-11 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-500">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-folder-open h-5 w-5" aria-hidden="true">
                            <path
                                d="m6 14 1.5-2.9A2 2 0 0 1 9.24 10H20a2 2 0 0 1 1.94 2.5l-1.54 6a2 2 0 0 1-1.95 1.5H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h3.9a2 2 0 0 1 1.69.9l.81 1.2a2 2 0 0 0 1.67.9H18a2 2 0 0 1 2 2v2"></path>
                        </svg>
                    </div>
                    <h2 class="mt-4 text-base font-semibold text-slate-900">No projects yet</h2>
                    <span class="mt-1 max-w-sm text-sm text-slate-600">Create your first project to organize and track its tasks</span>
                </div>
            @else
                @foreach($this->projects as $project)
                    <div class="projectContainer">
                        <section class="details">
                            <h2 class="truncate text-base font-semibold text-slate-900 transition-colors group-hover:text-orange-700">{{ $project->name }}</h2>
                            <span class="mt-1 line-clamp-2 text-sm text-slate-600">{{ $project->description }}</span>
                        </section>

                        <div class="options">
                            <button wire:click="editForm({{$project->id}})" type="button" aria-label="Edit Website Redesign" class="inline-flex h-10 items-center gap-1.5 whitespace-nowrap shrink-0 rounded-md px-2.5 text-sm font-medium text-slate-600 transition-colors duration-150 hover:bg-slate-100 hover:text-slate-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-orange-600">
                                <x-icons.edit/>
                                <span class="hidden md:inline">Edit</span>
                            </button>
                            <button type="button" aria-label="Delete Website Redesign" class="inline-flex h-10 items-center gap-1.5 whitespace-nowrap shrink-0 rounded-md px-2.5 text-sm font-medium text-slate-600 transition-colors duration-150 hover:bg-red-50 hover:text-red-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-red-600">
                                <x-icons.delete/>
                                <span class="hidden md:inline" wire:click="openDeleteConfirmationModal({{$project->id}})">Delete</span>
                            </button>
                        </div>
                    </div>
                @endforeach
            @endif
        </section>
    </div>

</div>




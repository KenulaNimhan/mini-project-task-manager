<?php

use App\Models\Project;
use Livewire\Component;

new class extends Component {
    public string $name = '';
    public string $description = '';

    public function save()
    {

        $validated = $this->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);

        Project::create($validated);

        return $this->redirect(route('project.index'));
    }

};
?>

<div class="m-auto mt-10 w-full min-w-2xl max-w-lg rounded-lg border border-slate-200 bg-white shadow-lg">
    <header class="flex justify-between p-6 border-b border-b-gray-300">
        <h2 class="text-lg font-semibold text-slate-900">New Project</h2>
        <button type="button" aria-label="Close modal" class="inline-flex h-8 w-8 items-center justify-center rounded-md text-slate-500 transition-colors duration-150 hover:bg-slate-100 hover:text-slate-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-orange-600">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x h-4 w-4" aria-hidden="true">
                <path d="M18 6 6 18"></path>
                <path d="m6 6 12 12"></path>
            </svg>
        </button>
    </header>
    <form wire:submit="save" class="space-y-5 p-6">
        <label class="flex flex-col">
            Project Name
            <input type="text" wire:model="name"
            class="mt-1.5 block w-full rounded-md border px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-orange-600 border-slate-300 bg-white hover:border-slate-400"
            >
            @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
        </label>

        <label class="flex flex-col">
            Description
            <textarea wire:model="description" rows="3" maxlength="255"
            class="mt-1.5 block w-full rounded-md border px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-orange-600 border-slate-300 bg-white hover:border-slate-400"
            ></textarea>
            @error('description') <span class="text-red-500">{{ $message }}</span> @enderror
        </label>

        <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-4">
            <button type="button" class="whitespace-nowrap shrink-0 rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition-colors duration-150 hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-orange-600">
                Cancel
            </button>
            <button type="submit" class="whitespace-nowrap shrink-0 rounded-md bg-orange-700 px-4 py-2 text-sm font-medium text-white transition-colors duration-150 hover:bg-orange-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-orange-600 focus-visible:ring-offset-2">
                Create Project
            </button>
        </div>
    </form>
</div>


@props([
    'projectName'
])

<div wire:click.self="closeModal" wire:keydown.escape.window="closeModal"
     class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 p-4 backdrop-blur-[1px]" role="dialog" aria-modal="true" aria-labelledby="delete-dialog-title">
    <div class="w-full max-w-md rounded-lg border border-slate-200 bg-white shadow-lg">
        <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
            <h2 id="delete-dialog-title" class="text-base font-semibold text-slate-900">
                Delete Project
            </h2>
            <button type="button" wire:click="closeModal" aria-label="Close confirmation dialog" class="inline-flex h-8 w-8 items-center justify-center rounded-md text-slate-500 transition-colors duration-150 hover:bg-slate-100 hover:text-slate-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-orange-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x h-4 w-4" aria-hidden="true">
                    <path d="M18 6 6 18"></path>
                    <path d="m6 6 12 12"></path>
                </svg>
            </button>
        </div>
        <div class="p-6">
            <p class="text-sm leading-relaxed text-slate-600">
                Are you sure you want to delete
                <span class="font-semibold text-slate-900">
                    {{ $projectName }}
                </span>
                ? This project has no tasks and will be permanently removed.
            </p>
            <div class="mt-6 flex items-center justify-end gap-3">
                <button type="button" wire:click="closeModal" class="whitespace-nowrap shrink-0 rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition-colors duration-150 hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-orange-600">
                    Cancel
                </button>
                <button type="button" wire:click="delete" class="inline-flex items-center gap-1.5 whitespace-nowrap shrink-0 rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white transition-colors duration-150 hover:bg-red-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-red-600 focus-visible:ring-offset-2">
                    <x-icons.delete/>
                    Delete Project
                </button>
            </div>
        </div>
    </div>
</div>

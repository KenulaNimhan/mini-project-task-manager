@props([
    'title',
    'submitText',
    'onSubmit'
])

<div wire:click.self="closeModal" wire:keydown.escape.window="closeModal"
     class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40">
    <div class="h-fit w-full min-w-2xl max-w-lg rounded-lg border border-slate-200 bg-white shadow-lg">
        <header class="flex justify-between p-6 border-b border-b-gray-300">
            <h2 class="text-lg font-semibold text-slate-900">{{ $title }}</h2>
            <button type="button" wire:click="closeModal" aria-label="Close modal" class="inline-flex h-8 w-8 items-center justify-center rounded-md text-slate-500 transition-colors duration-150 hover:bg-slate-100 hover:text-slate-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-orange-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x h-4 w-4" aria-hidden="true">
                    <path d="M18 6 6 18"></path>
                    <path d="m6 6 12 12"></path>
                </svg>
            </button>
        </header>
        <form wire:submit="{{ $onSubmit }}" class="flex flex-col gap-3 p-6">
            <label for="name">
                Project Name
                <span class="text-red-600" aria-hidden="true">*</span>
                <input type="text" id="name" name="name" wire:model="name" class="input" x-init="$nextTick(() => $el.focus())">
                @error('name') <span class="errorMsg">{{ $message }}</span> @enderror
            </label>

            <label for="description">
                Description
                <textarea wire:model="description" id="description" name="description" rows="3" maxlength="255" class="input"></textarea>
                @error('description') <span class="errorMsg">{{ $message }}</span> @enderror
            </label>

            <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-4">
                <button type="button" wire:click="closeModal" class="whitespace-nowrap shrink-0 rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition-colors duration-150 hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-orange-600">
                    Cancel
                </button>
                <button type="submit" class="submitBtn">
                    {{ $submitText }}
                </button>
            </div>
        </form>
    </div>
</div>

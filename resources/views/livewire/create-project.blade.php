<div class="m-auto mt-[8em] h-fit w-full min-w-2xl max-w-3xl">
    <a href="{{ route('project.index') }}" type="button" class="mb-5 inline-flex items-center gap-1.5 whitespace-nowrap shrink-0 rounded-md py-1 text-sm font-medium text-slate-600 transition-colors duration-150 hover:text-orange-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-orange-600">
        <x-icons.back/>
        Back to Projects
    </a>
    <header class="flex justify-between p-6 border-b border-b-gray-300">
        <h2 class="text-2xl font-semibold text-slate-900">New Project</h2>
    </header>
    <form wire:submit.prevent="store" class="flex flex-col gap-10 p-6">
        <label for="name">
            Project Name
            <span class="text-red-600" aria-hidden="true">*</span>
            <input type="text" id="name" wire:model="name" class="input" x-init="$nextTick(() => $el.focus())">
            @error('name') <span class="errorMsg">{{ $message }}</span> @enderror
        </label>

        <label for="description">
            Description
            <textarea id="description" wire:model="description" rows="5" maxlength="255" class="input"></textarea>
            @error('description') <span class="errorMsg">{{ $message }}</span> @enderror
        </label>

        <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-4">
            <button type="submit" class="submitBtn">
                Create Project
            </button>
        </div>
    </form>
</div>

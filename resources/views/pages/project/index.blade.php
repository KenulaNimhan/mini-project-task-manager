<x-layouts::app/>

<div class="projectSection">
    <header>
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Projects</h1>
            <span class="mt-1 text-sm text-slate-600">{{ $projects->count() }} projects total</span>
        </div>
        <a href="{{ route('project.create') }}" class="submitBtn">+ New Project</a>
    </header>
    <section class="flex flex-col justify-center rounded-lg border border-slate-200 bg-white mt-5">
        @if($projects->isEmpty())
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
            @foreach($projects as $project)
                <div class="projectContainer" >
                    <a class="projectDetails flex-3" href="{{ route('project.show', $project) }}">
                        <h2 class="truncate text-base font-semibold text-slate-900 transition-colors group-hover:text-orange-700">{{ $project->name }}</h2>
                        <span class="mt-1 line-clamp-2 text-sm text-slate-600">{{ $project->description }}</span>
                    </a>
                    <div class="flex shrink-0 items-center justify-between gap-6 sm:justify-end">
                        <div class="min-w-37">
                            <div class="text-sm font-medium text-slate-700 tabular-nums">0/0 Tasks Completed</div>
                            <div class="mt-1.5 h-1.5 w-full overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full transition-all duration-200 bg-orange-600" style="width: 0%;"></div>
                            </div>
                        </div>
                        <div class="flex items-center gap-1">
                            <a href="{{ route('project.edit', $project) }}" aria-label="Edit Project" class="inline-flex h-10 items-center gap-1.5 whitespace-nowrap shrink-0 rounded-md px-2.5 text-sm font-medium text-slate-600 transition-colors duration-150 hover:bg-slate-100 hover:text-slate-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-orange-600">
                                <x-icons.edit/>
                                <span class="hidden md:inline">Edit</span>
                            </a>
                            <form action="{{ route('project.destroy', $project) }}" method="POST">
                                @csrf
                                @method('DELETE')
                            <button type="submit" aria-label="Delete Project" class="inline-flex h-10 items-center gap-1.5 whitespace-nowrap shrink-0 rounded-md px-2.5 text-sm font-medium text-slate-600 transition-colors duration-150 hover:bg-red-50 hover:text-red-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-red-600">
                                <x-icons.delete/>
                                <span class="hidden md:inline">Delete</span>
                            </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </section>
</div>

<?php

use App\Models\Project;
use Livewire\Component;

new class extends Component {
    public Project $project;
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
                    class="text-2xl font-bold tracking-tight text-slate-900 [text-wrap:balance]">
                    {{ $project->name }}
                </h1>
                <p class="mt-2 max-w-2xl text-sm leading-relaxed text-slate-600">
                    {{ $project->description }}
                </p>
            </div>
        </div>
        {{--        <div class="mt-5 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-4">--}}
        {{--            <span class="text-sm font-medium text-slate-700 tabular-nums">0/0 Tasks Completed</span>--}}
        {{--            <div class="h-1.5 w-full overflow-hidden rounded-full bg-slate-100 sm:w-48">--}}
        {{--                <div class="h-full transition-all duration-200 bg-orange-600" style="width: 0%;"></div>--}}
        {{--            </div>--}}
        {{--        </div>--}}
    </div>

    <div class="mt-10 overflow-hidden rounded-lg border border-slate-200 bg-white">
        <form action="{{ route('task.store') }}" method="POST"
              class="border-b border-slate-200 bg-slate-50/50 p-4 sm:p-5">
            @csrf
            <label for="new-task-title" class="sr-only">Add a new task</label>
            <div class="flex flex-col gap-3 sm:flex-row">
                <input name="description" id="new-task-title" placeholder="Add a new task..." aria-invalid="false"
                       class="h-10 flex-1 rounded-md border px-3.5 text-sm text-slate-900 placeholder:text-slate-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-orange-600 border-slate-300 bg-white hover:border-slate-400"
                       type="text" value="">
                <input name="project_id" hidden="" value="{{ $project->id }}">
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
        </form>
        @if($project->tasks->isEmpty())
            <div class="px-6 py-12 text-center">
                <x-icons.tasks/>
                <p class="text-sm font-medium text-slate-700">No tasks in this project yet</p>
                <p class="mt-1 text-sm text-slate-500">Enter a task title above to add your first task.</p>
            </div>
        @else
            @foreach($project->tasks as $task)
                <div class="taskContainer">
                    <section class="details">
                        <span class="mt-1 line-clamp-2 text-sm text-slate-600">{{ $task->description }}</span>
                    </section>
                </div>
            @endforeach
        @endif
    </div>

    {{--    <livewire:create-task :project-id="$project->id" />--}}

</div>


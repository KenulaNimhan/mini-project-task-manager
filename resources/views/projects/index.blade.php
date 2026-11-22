@extends('layout.layout')
@section('content')

    <div class="projectSection">
        <section>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Projects</h1>
                <span class="mt-1 text-sm text-slate-600"> projects total</span>
            </div>
            <button type="button"
            class="h-fit rounded-md p-3 pr-5 pl-5
            bg-orange-600 text-xs font-medium text-white transition-colors duration-150
            hover:bg-orange-800 focus-visible:outline-none focus-visible:ring-2"
            >
                + New Project
            </button>
        </section>
        <section class="flex flex-col items-center justify-center rounded-lg border border-slate-200 bg-white mt-5 px-6 py-16 text-center">
            @if($projects->isEmpty())
                <div>
                    <div class="m-auto flex h-11 w-11 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-500"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-folder-open h-5 w-5" aria-hidden="true"><path d="m6 14 1.5-2.9A2 2 0 0 1 9.24 10H20a2 2 0 0 1 1.94 2.5l-1.54 6a2 2 0 0 1-1.95 1.5H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h3.9a2 2 0 0 1 1.69.9l.81 1.2a2 2 0 0 0 1.67.9H18a2 2 0 0 1 2 2v2"></path></svg>
                    </div>
                    <h2 class="mt-4 text-base font-semibold text-slate-900">No projects yet</h2>
                    <span class="mt-1 max-w-sm text-sm text-slate-600">Create your first project to organize and track its tasks</span>
                </div>
            @endif
        </section>
    </div>

@endsection

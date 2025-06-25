<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a wire:navigate href="{{ route('project.index') }}"><i class='bx bx-line-chart'></i>{{ ucfirst(Auth::user()->organization->name) }}</a></li>
            <li class="breadcrumb-item"><a wire:navigate href="{{ route('project.index') }}">All Projects</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $project->name }}</li>
        </ol>
    </nav>
    <livewire:projects.components.project-tabs :project="$project" />
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="column-box">
                <x-calendar for="project" :id="$project->id" />
            </div>
        </div>
    </div>
</div>

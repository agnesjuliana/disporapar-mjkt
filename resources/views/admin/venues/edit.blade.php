<x-layouts.app title="Edit Venue" current-page="venues" role="ADMIN">
    <div class="mb-5">
        <a href="{{ route('admin.venues.index') }}" class="text-sm text-slate-400 hover:text-indigo-500 flex items-center gap-1 w-fit">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            Kembali ke Manajemen Venue
        </a>
        <h2 class="page-title mt-2">Edit Venue</h2>
        <p class="page-subtitle">{{ $venue->name }}</p>
    </div>

    <div class="max-w-3xl">
        <div class="card">
            <form method="POST" action="{{ route('admin.venues.update', $venue) }}" enctype="multipart/form-data">
                @include('admin.venues._form', ['venue' => $venue])
            </form>
        </div>
    </div>
</x-layouts.app>

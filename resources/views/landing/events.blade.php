<x-layouts.landing title="Semua Event">
    <section class="pt-32 pb-20 px-5 sm:px-8 bg-white dark:bg-brand-dark-bg min-h-screen">
        <div class="max-w-7xl mx-auto">
            <div class="mb-8">
                <span class="badge badge-orange">Agenda Daerah</span>
                <h1 class="mt-3 text-3xl font-extrabold text-slate-900 dark:text-white">Semua Event Aktif</h1>
                <p class="mt-2 text-sm text-slate-500 dark:text-orange-100/65 max-w-xl">
                    Seluruh event yang telah disetujui Disporapar Mojokerto, terbuka untuk masyarakat dan tenant UMKM.
                </p>
            </div>

            <form method="GET" action="{{ route('landing.events') }}" class="mb-8 flex flex-col sm:flex-row gap-3 max-w-lg">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama event atau organizer..." class="form-input">
                <button type="submit" class="btn btn-primary shrink-0 justify-center">
                    <i data-lucide="search" class="w-4 h-4"></i>
                    Cari
                </button>
                @if ($search !== '')
                    <a href="{{ route('landing.events') }}" class="btn btn-secondary shrink-0 justify-center">Reset</a>
                @endif
            </form>

            @if ($events->isEmpty())
                <div class="card text-center py-16">
                    <i data-lucide="calendar-x" class="w-10 h-10 mx-auto text-slate-300"></i>
                    <p class="mt-3 text-sm text-slate-500 dark:text-orange-100/65">Tidak ada event yang ditemukan.</p>
                </div>
            @else
                <p class="text-sm text-slate-400 dark:text-orange-100/50 mb-4">Menampilkan {{ $events->total() }} event aktif</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($events as $event)
                        <x-landing.event-card :event="$event" />
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $events->links() }}
                </div>
            @endif
        </div>
    </section>
</x-layouts.landing>

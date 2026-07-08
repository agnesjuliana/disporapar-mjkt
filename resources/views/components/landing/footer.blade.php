<footer class="landing-footer py-12 px-5 sm:px-8">
    <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
        <div class="flex items-center gap-2.5">
            <img src="{{ asset('images/brand-white.png') }}" alt="Logo" class="w-8 h-8 object-contain">
            <div>
                <p class="text-sm font-bold text-white">{{ config('app.name', 'WARTA WILWATIKTA') }}</p>
                <p class="text-xs text-orange-100/60">Dinas Kepemudaan, Olahraga &amp; Pariwisata Mojokerto</p>
            </div>
        </div>
        <p class="text-xs text-orange-100/50">
            &copy; {{ date('Y') }} {{ config('app.name', 'WARTA WILWATIKTA') }}. Bumi Wilwatikta, Kabupaten Mojokerto.
        </p>
    </div>
</footer>

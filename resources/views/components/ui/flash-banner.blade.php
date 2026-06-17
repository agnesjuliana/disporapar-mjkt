@props([
    'type' => session('status') ? 'success' : 'error',
    'message' => session('status') ?? $errors->first(),
])

@if ($message)
    @php($isError = $type === 'error')

    <div {{ $attributes->merge([
        'class' => 'flex items-center gap-3 p-3.5 rounded-xl mb-5 ' . (
            $isError
                ? 'bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300'
                : 'bg-green-50 dark:bg-green-950/40 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300'
        ),
        'data-auto-dismiss' => '4500',
    ]) }}>
        <i data-lucide="{{ $isError ? 'x-circle' : 'check-circle' }}" class="w-4 h-4 flex-shrink-0"></i>
        <span class="text-sm">{{ $message }}</span>
    </div>
@endif

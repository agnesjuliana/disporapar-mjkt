@props(['status'])

@php
    $normalized = strtoupper((string) $status);
    $map = [
        'ACTIVE' => ['Aktif', 'green'],
        'INACTIVE' => ['Tidak Aktif', 'gray'],
        'SUSPENDED' => ['Dibekukan', 'red'],
        'PENDING' => ['Menunggu', 'yellow'],
        'APPROVED' => ['Disetujui', 'green'],
        'REJECTED' => ['Ditolak', 'red'],
        'CANCELLED' => ['Dibatalkan', 'gray'],
        'ASSIGNED' => ['Ditugaskan', 'blue'],
        'NOT_CHECKED_IN' => ['Belum Check-in', 'gray'],
        'PRESENT' => ['Hadir', 'green'],
        'ABSENT' => ['Tidak Hadir', 'red'],
    ];
    [$label, $color] = $map[$normalized] ?? [str($status)->headline(), 'gray'];
@endphp

<span {{ $attributes->merge(['class' => 'badge badge-' . $color]) }}>{{ $label }}</span>

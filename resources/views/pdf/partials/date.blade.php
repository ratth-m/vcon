{{-- วันที่รูปแบบ ..../..../.... : @include('pdf.partials.date', ['value' => Carbon|string|null, 'w' => '25mm']) --}}
@php
    $__d = $value ?? null;
    if ($__d && ! $__d instanceof \DateTimeInterface) {
        try { $__d = \Illuminate\Support\Carbon::parse($__d); } catch (\Throwable $e) { $__d = null; }
    }
@endphp
@if ($__d)
<span class="fill" style="width: {{ $w ?? '25mm' }};">{{ $__d->format('d/m/Y') }}</span>
@else
<span style="white-space: nowrap;">......../......../........</span>
@endif

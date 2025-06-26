@php
    $goal = $event->goal ?? 0;
    $raised = $event->amount_raised ?? ($event->total_donated ?? 0);
    $progress = ($goal > 0) ? min(100, ($raised / $goal) * 100) : 0;
@endphp

<h2>{{ number_format($raised) }} VND</h2>
<p>trong tổng số tiền là {{ number_format($goal) }} VND</p>

<div class="progress-bar">
    <div class="progress" style="width: {{ $progress }}%;">
        {{ number_format($progress, 0) }}%
    </div>
</div>

@if (isset($donations))
    <p>{{ $donations->count() }} lượt quyên góp</p>
@endif

<h2>{{ number_format($event->total_donated) }} VND</h2>
<p>trong tổng số tiền là {{ number_format($event->goal) }} VND</p>
<div class="progress-bar">
    @php
        $percent = $event->goal > 0 ? ($event->total_donated / $event->goal) * 100 : 0;
    @endphp
    <div class="progress" style="width: {{ $percent }}%;">
        {{ round($percent) }}%
    </div>
</div>
<p>{{ $donations->count() }} lượt quyên góp</p>
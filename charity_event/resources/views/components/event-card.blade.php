@php
    $goal = $event->goal;
    $raised = $event->amount_raised;
    $progress = ($goal > 0) ? min(100, ($raised / $goal) * 100) : 0;
    $role = Auth::user()->role;

    $detailRoute = match ($role) {
        'admin' => route('admin.event.details', ['id' => $event->event_id]),
        'organization' => route('organization.event.details', ['id' => $event->event_id]),
        'donor' => route('donor.event.details', ['id' => $event->event_id]),
        default => '#',
    };

    $buttonText = $role === 'donor' ? 'Quyên góp' : 'Xem';
@endphp


<div class="event-card">
    <h3>
        @if ($role === 'admin' && isset($event->pending) && $event->pending > 0)
            <span class="warning-icon"><big>❗</big></span>
        @endif
        {{ htmlspecialchars($event->name) }}
    </h3>

    <div class="event-description">{!! nl2br(e($event->description)) !!}</div>

    <p><strong>Tổ chức:</strong> {{ htmlspecialchars($event->organization) }}</p>
    <p><strong>Người phụ trách:</strong> {{ htmlspecialchars($event->organizer_name) }}</p>
    <p><strong>Địa điểm hỗ trợ:</strong> {{ htmlspecialchars($event->location) }}</p>

    <div class="progress-bar">
        <div class="progress" style="width: {{ $progress }}%;">
            {{ number_format($progress, 0) }}%
        </div>
    </div>

    <button onclick="window.location.href='{{ $detailRoute }}'">{{ $buttonText }}</button>
</div>

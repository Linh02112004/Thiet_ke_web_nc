<div class="event-card" style="background-color: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
    <h3 style="font-weight: bold; font-size: 18px; margin-bottom: 10px;">{{ $event->event_name }}</h3>
    <p style="margin: 4px 0;">{{ Str::limit($event->description, 100) }}</p>
    <p style="margin: 4px 0;"><strong>Tổ chức:</strong> {{ $event->organization->organization_name ?? 'N/A' }}</p>
    <p style="margin: 4px 0;"><strong>Người phụ trách:</strong> {{ $event->organizer_name }}</p>
    <p style="margin: 4px 0;"><strong>Địa điểm hỗ trợ:</strong> {{ $event->location }}</p>

    @php
        $raised = $event->raised ?? 0;
        $goal = $event->goal;
        $percent = $goal > 0 ? min(100, round(($raised / $goal) * 100)) : 0;
    @endphp

    <div style="background-color: #e0e0e0; height: 20px; border-radius: 10px; overflow: hidden; margin: 10px 0;">
        <div style="width: {{ $percent }}%; height: 100%; background-color: #28a745; text-align: center; color: #fff; font-size: 12px;">
            {{ $percent }}%
        </div>
    </div>

    <a href="{{ route('organization.event.details', ['id' => $event->id]) }}" 
       class="btn btn-success" 
       style="display: block; background-color: #28a745; color: white; text-align: center; padding: 8px; border-radius: 5px; text-decoration: none;">
        Xem
    </a>
</div>

@extends('layouts.app')

@section('title', 'Organization Dashboard')

@section('content')
    <h2 class="text-2xl font-bold text-center mb-4">DANH SÁCH SỰ KIỆN</h2>
    <div class="text-right mb-4">
        <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded" data-modal="create-event-modal">Tạo sự kiện mới</button>
    </div>

    {{-- Danh sách sự kiện đang diễn ra --}}
    <h3 class="text-xl font-semibold mb-2">Sự kiện đang diễn ra</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        @forelse ($ongoingEvents as $event)
            @include('organization.partials.event-card', ['event' => $event])
        @empty
            <p>Không có sự kiện nào.</p>
        @endforelse
    </div>

    {{-- Danh sách sự kiện đã hoàn thành --}}
    <h3 class="text-xl font-semibold mb-2">Sự kiện đã hoàn thành</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @forelse ($completedEvents as $event)
            @include('organization.partials.event-card', ['event' => $event])
        @empty
            <p>Không có sự kiện nào.</p>
        @endforelse
    </div>

    {{-- Popups --}}
    @include('organization.modals.update-info')
    @include('organization.modals.change-password')
    @include('organization.modals.create-event')
@endsection

@push('scripts')
<script src="{{ asset('js/organization.js') }}"></script>
@endpush

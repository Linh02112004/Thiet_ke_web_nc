@extends('layouts.master')

@section('title', '🌱 HY VỌNG - ' . $event->event_name)

@section('content')
<div class="container">
    <h1>{{ $event->event_name }}</h1>
    <div class="content-wrapper">
        <div class="container-left">
            <hr>
            @include('components.event-info')

            @if ($event->donation_count == 0)
                <form method="POST" action="{{ route('organization.event.delete', ['id' => $event->id]) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Xóa sự kiện</button>
                </form>
            @endif
            <button onclick="openModal('compareModal')">So sánh thay đổi</button>

            <hr>
            <h3>Bình luận</h3>
            @include('components.comment-box')

            @include('components.comment-section')
        </div>

        <div class="container-right">
            <hr>
            <div class="right-top">
            @include('components.progress-bar')
            </div>

            <div class="right-bottom">
                @include('components.donation-table')
            </div>
        </div>
    </div>
</div>

@if ($edit)
    @include('modals.compare-edit', ['event' => $event, 'original' => $original, 'edit' => $edit])
@endif
@endsection

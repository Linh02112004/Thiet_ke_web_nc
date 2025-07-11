@extends('layouts.master')

@section('title', '🌱 HY VỌNG - ' . $event->event_name)

@section('content')
@php
    $goal = $event->goal;
    $raised = $event->amount_raised;
    $progress = ($goal > 0) ? min(100, ($raised / $goal) * 100) : 0;
@endphp

<div class="container">
    <h1>{{ $event->event_name }}</h1>
    <div class="content-wrapper">
        <div class="container-left">
            <hr>
            @include('components.event-info')

            <hr>
            <h3>Bình luận</h3>
            @include('components.comment-box')

            @include('components.comment-section')
        </div>

        <div class="container-right">
            <hr>
            <div class="right-top">
                @include('components.progress-bar')

                @if ($progress < 100)
                    <button class="btn btn-donate" id="donateButton" onclick="showModal()">Quyên góp</button>
                @endif

            </div>

            <div class="right-bottom">
                @include('components.donation-table')
            </div>
        </div>
    </div>

    @include('modals.donation-modal', ['event' => $event, 'bankCode' => $bankCode])

</div>
@endsection
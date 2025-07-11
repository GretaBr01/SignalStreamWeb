@extends('layouts.master')

@section('title', __('howWorksMessages.title'))

@section('body')
<div class="container py-5 page-content" style="max-width: 900px;">
    <h1 class="mb-4 text-purple2 fw-bold"> {{__('howWorksMessages.title')}}</h1>

    <p class="lead text-muted mb-5">
        {!!__('howWorksMessages.intro')!!}
    </p>

    <section class="mb-5 p-4 rounded shadow-sm" style="background: linear-gradient(135deg, #d8caff, #b393f6);">
        <h3 class="mb-3 text-purple2 fw-semibold">{!!__('howWorksMessages.acquisition')!!}</h3>
        <p>
            {!!__('howWorksMessages.acquisition_description')!!}
        </p>
        <ul class="mb-0" style="list-style-type: disc; padding-left: 1.25rem;">
            <li>{!!__('howWorksMessages.emg')!!}</li>
            <li>{!!__('howWorksMessages.imu')!!}</li>
        </ul>
        <p class="mt-3">{!!__('howWorksMessages.hardware_note')!!}</p>
    </section>

    <section class="mb-5 p-4 rounded shadow-sm" style="background: linear-gradient(135deg, #d8caff, #b393f6);">
        <h3 class="mb-3 text-purple2 fw-semibold">{!!__('howWorksMessages.saving')!!}</h3>
        <p>
            {!!__('howWorksMessages.saving_description')!!}
        </p>
        <ul class="mb-0" style="list-style-type: disc; padding-left: 1.25rem;">
            <li>{!!__('howWorksMessages.gesture_category')!!}</li>
            <li>{!!__('howWorksMessages.personal_notes')!!}</li>
        </ul>
        <p>{!!__('howWorksMessages.saving_format')!!}</p>
    </section>

    <section class="mb-5 p-4 rounded shadow-sm" style="background: linear-gradient(135deg, #d8caff, #b393f6);">
        <h3 class="mb-3 text-purple2 fw-semibold">{!!__('howWorksMessages.history')!!}</h3>
        <p>{!!__('howWorksMessages.history_description')!!}</p>
        <ul class="mb-0" style="list-style-type: disc; padding-left: 1.25rem;">
            @foreach(__('howWorksMessages.history_items') as $item)
                <li>{!!$item!!}</li>
            @endforeach
        </ul>
    </section>

    <section class="mb-5 p-4 rounded shadow-sm" style="background: linear-gradient(135deg, #d8caff, #b393f6);">
        <h3 class="mb-3 text-purple2 fw-semibold">{!!__('howWorksMessages.recognition')!!}</h3>
        <p>
            {!!__('howWorksMessages.recognition_description')!!}
        </p>
        <ul style="list-style-type: disc; padding-left: 1.25rem;">
            @foreach(__('howWorksMessages.gestures') as $item)
                <li>{!! $item !!}</li>
            @endforeach
        </ul>
        <p>
            {!!__('howWorksMessages.recognition_accuracy')!!}
        </p>
    </section>

    <section class="mb-5 p-4 rounded shadow-sm" style="background: linear-gradient(135deg, #d8caff, #b393f6);">
        <h3 class="mb-3 text-purple2 fw-semibold">{!!__('howWorksMessages.who')!!}</h3>
        <p>{!!__('howWorksMessages.who_description')!!}</p>
        <ul class="mb-0" style="list-style-type: disc; padding-left: 1.25rem;">
            @foreach(__('howWorksMessages.roles') as $item)
                <li>{!! $item !!}</li>
            @endforeach
        </ul>
    </section>

    <section class="mb-4 p-4 rounded shadow-sm" style="background: linear-gradient(135deg, #d8caff, #b393f6);">
        <h3 class="mb-3 text-purple2 fw-semibold">{!!__('howWorksMessages.hardware')!!}</h3>
        <ul style="list-style-type: disc; padding-left: 1.25rem;">
            @foreach(__('howWorksMessages.hardware_list') as $item)
                <li>{!! $item !!}</li>
            @endforeach
        </ul>
        <p>{!!__('howWorksMessages.hardware_note_2')!!}</p>
    </section>

    <section class="mb-4 p-4 rounded shadow-sm" style="background: linear-gradient(135deg, #d8caff, #b393f6);">
        <h3 class="mb-3 text-purple2 fw-semibold">{!!__('howWorksMessages.support')!!}</h3>
        <p>{!!__('howWorksMessages.support_text')!!}</p>
    </section>

        <section class="mb-5 p-4 rounded shadow-sm" style="background: linear-gradient(135deg, #d8caff, #b393f6);">
        <h3 class="mb-3 text-purple2 fw-semibold">{!!__('howWorksMessages.architecture')!!}</h3>
        <p>
            {!!__('howWorksMessages.architecture_description')!!}
        </p>
        <div class="text-center my-4">
            <img src="{{ asset('images/emgesture_architecture.png') }}" alt="Architettura del sistema EMGesture" class="img-fluid rounded shadow-sm" style="max-width: 100%; height: auto;">
            <small class="d-block text-muted mt-2">{!!__('howWorksMessages.architecture_caption')!!}</small>
        </div>
        <p class="mt-4">
            {!!__('howWorksMessages.architecture_cta')!!}
        </p>
        <a href="https://github.com/GretaBr01/EMGesture" target="_blank" class="btn btn-primary rounded-pill px-4">
            {!!__('howWorksMessages.github_button')!!}
        </a>
    </section>
</div>
@endsection

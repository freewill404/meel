@extends('layout.base-template', [
    'title' => 'Account | Meel.me',
])

@section('content')

    @include('layout.header', ['title' => 'Account'])

    <div class="max-w-lg mx-auto mt-8">

        <h2 class="mb-4">Upcoming</h2>
        @foreach($emailSchedules->where('next_occurrence', '!=', null) as $emailSchedule)
            @include('account.partial.email-schedule', compact($emailSchedule))
        @endforeach

        <h2 class="my-4">Ended</h2>
        @foreach($emailSchedules->where('next_occurrence', null) as $emailSchedule)
            @include('account.partial.email-schedule', compact($emailSchedule))
        @endforeach

    </div>

@endsection

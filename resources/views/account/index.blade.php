@extends('layout.base-template', [
    'title'       => 'Meel.me | Account',
    'description' => 'SEO description',
])

@section('header')
    @include('layout.account-header')
@endsection

@section('content')

    <div class="max-w-lg mx-auto mt-2">

        <h1 class="mb-8">Account</h1>

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

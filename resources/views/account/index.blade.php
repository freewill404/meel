@extends('layout.base-template', [
    'title' => 'Account | Meel.me',
])

@section('content')

    @include('layout.header', ['title' => 'Account'])

    <div class="max-w-lg mx-auto mt-8 mb-16">

        <h2 class="mb-4">Upcoming</h2>

        <div class="pl-4">
            @forelse($emailSchedules->where('next_occurrence', '!=', null) as $emailSchedule)
                <email-schedule email-schedule-id="{{ $emailSchedule->id }}"
                                :is-recurring="{{ json_encode($emailSchedule->is_recurring) }}"
                                :times-sent="{{ $emailSchedule->times_sent }}"
                                when="{{ $emailSchedule->when }}"
                                initial-what="{{ $emailSchedule->what }}"
                                next-occurrence="{{ $emailSchedule->next_occurrence }}"
                                last-sent-at="{{ $emailSchedule->last_sent_at }}"
                ></email-schedule>
            @empty
                <p>
                    You have no upcoming emails.
                </p>
            @endforelse
        </div>


        <h2 class="mt-8 mb-4">Ended</h2>

        <div class="pl-4">
            @forelse($emailSchedules->where('next_occurrence', null) as $emailSchedule)
                <email-schedule email-schedule-id="{{ $emailSchedule->id }}"
                                :is-recurring="{{ json_encode($emailSchedule->is_recurring) }}"
                                :times-sent="{{ $emailSchedule->times_sent }}"
                                when="{{ $emailSchedule->when }}"
                                initial-what="{{ $emailSchedule->what }}"
                                next-occurrence="{{ $emailSchedule->next_occurrence }}"
                                last-sent-at="{{ $emailSchedule->last_sent_at }}"
                ></email-schedule>
            @empty
                <p>
                    You have no ended schedules.
                </p>
            @endforelse
        </div>
    </div>

@endsection

@extends('layout.base-template', [
    'title' => 'Account | Meel.me',
])

@section('content')

    @include('layout.header', ['title' => 'Account'])

    <div class="max-w-lg mx-auto mt-8 mb-16">

        <div class="flex justify-between">
            <h2 class="mb-4">Upcoming</h2>

            <a class="text-black text-sm" href="{{ route('user.more') }}">Emails left: {{ $user->emails_left }}</a>
        </div>

        <div class="sm:pl-4">
            @forelse($schedules->where('next_occurrence', '!=', null) as $schedule)
                <email-schedule schedule-id="{{ $schedule->id }}"
                                :is-recurring="{{ json_encode($schedule->is_recurring) }}"
                                :times-sent="{{ $schedule->times_sent }}"
                                when="{{ $schedule->when }}"
                                initial-what="{{ $schedule->what }}"
                                next-occurrence="{{ $schedule->next_occurrence }}"
                                last-sent-at="{{ $schedule->last_sent_at ? $schedule->last_sent_at->setTimezone($user->timezone) : null }}"
                ></email-schedule>
            @empty
                <p>
                    You have no upcoming emails.
                </p>
            @endforelse
        </div>


        <h2 class="mt-8 mb-4">Ended</h2>

        <div class="sm:pl-4">
            @forelse($schedules->where('next_occurrence', null)->reverse() as $schedule)
                <email-schedule schedule-id="{{ $schedule->id }}"
                                :is-recurring="{{ json_encode($schedule->is_recurring) }}"
                                :times-sent="{{ $schedule->times_sent }}"
                                when="{{ $schedule->when }}"
                                initial-what="{{ $schedule->what }}"
                                next-occurrence="{{ $schedule->next_occurrence }}"
                                last-sent-at="{{ $schedule->last_sent_at ? $schedule->last_sent_at->setTimezone($user->timezone) : null }}"
                ></email-schedule>
            @empty
                <p>
                    You have no ended schedules.
                </p>
            @endforelse
        </div>
    </div>

@endsection

<div class="border-b py-1 mb-2">
    <div>{{ $emailSchedule->what }}</div>

    <div class="flex items-center text-xs max-w-xs mt-2">

        <div class="flex items-center" title="{{ $emailSchedule->when }}">
            {{ $emailSchedule->next_occurrence ?? $emailSchedule->last_sent_at }}
            @if($emailSchedule->is_recurring)
                <span class="inline-block ml-2 h-4 w-4">@include('helpers.svg.recurring')</span>
            @endif
        </div>

        @if($emailSchedule->emailScheduleHistories->count())
            <div class="flex items-center ml-auto" title="Last sent at: {{ $emailSchedule->last_sent_at }}">
                <span class="inline-block h-4 w-4">@include('helpers.svg.envelope')</span>
                <span class="ml-2">{{ $emailSchedule->emailScheduleHistories->count() }}x</span>
            </div>
        @endif

    </div>

</div>
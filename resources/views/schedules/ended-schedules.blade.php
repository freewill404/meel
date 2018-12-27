@extends('layout.base-template', [
    'title' => 'Ended email schedules',
])

@section('content')
    @include('layout.header', ['title' => 'Schedules', 'maxWidth' => 'max-w-md'])

    <div class="max-w-sm mx-auto mt-8 mb-16">

        <div class="flex justify-between text-sm">
            <span>
                <a class="text-grey-dark mr-4" href="{{ route('user.schedules') }}">upcoming</a>
                <span class=" font-semibold">ended</span>
            </span>

            <a class="text-black" href="{{ route('home') }}">new</a>
        </div>


        @if($hasEndedSchedules)
        <div class="mt-8">
            <email-schedules type="ended"></email-schedules>
        </div>
        @endif


        <div id="no-schedules-drawing" class="flex flex-col justify-center text-center mt-16" style="{{ $hasEndedSchedules ? 'display:none;' : '' }}">
            <div class="w-32 sm:w-48 mx-auto">
                @include('helpers.drawings.no-data')
            </div>

            <div class="mt-8">
                No ended schedules to display {{ $noSchedulesYet ? 'yet' : '' }}
            </div>
        </div>

    </div>
@endsection

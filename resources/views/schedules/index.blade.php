@extends('layout.base-template', [
    'title' => 'Upcoming email schedules',
])

@section('content')
    @include('layout.header', ['title' => 'Schedules', 'maxWidth' => 'max-w-md'])

    <div class="max-w-sm mx-auto mt-8 mb-16">

        <div class="flex justify-between text-sm">
            <span>
                <span class="mr-4 font-semibold">upcoming</span>
                <a class="text-grey-dark" href="{{ route('user.schedules.ended') }}">ended</a>
            </span>

            <a class="text-black" href="{{ route('home') }}">new</a>
        </div>


        @if($hasUpcomingSchedules)
        <div class="mt-8">
            <email-schedules type="upcoming"></email-schedules>
        </div>
        @endif


        <div id="no-schedules-drawing" class="flex flex-col justify-center text-center mt-16" style="{{ $hasUpcomingSchedules ? 'display:none;' : '' }}">
            <div class="w-48 sm:w-64 mx-auto">
                @include('helpers.drawings.empty')
            </div>

            <div class="mt-8">
                You have no upcoming scheduled emails {{ $noSchedulesYet ? 'yet' : '' }}
            </div>

            <div class="mt-4">
                <a class="text-black font-semibold" href="{{ route('home') }}">
                    {{ $noSchedulesYet ? 'Create your first schedule' : 'Create a new schedule' }}
                </a>
            </div>
        </div>

    </div>
@endsection

@extends('layout.admin-template', ['title' => 'Input logs'])

@section('content')
    <div class="max-w-md mx-auto font-mono">

        <br>

        @foreach($inputLogs as $sessionId => $logs)
        <div class="p-4">
            <div class="flex justify-between max-w-xs">
                <div class="font-bold">Source: {{ $logs[0]->source }}</div>

                <form method="post" action="{{ route('admin.inputLogs.delete') }}">
                    {{ method_field('DELETE') }}
                    {{ csrf_field() }}
                    <input type="hidden" name="session_id" value="{{ $sessionId }}">
                    <button class="text-red-dark">delete</button>
                </form>
            </div>

            @foreach ($logs as $log)
                <div class="flex items-center pl-2 my-1 border-l-4 {{ $log->usable ? 'border-green' : 'border-red' }} hover:bg-grey-light">
                    <span class="-ml-6 mr-4">{!! $log->recurring ? 'R' : '&nbsp;' !!}</span>
                    <span>{{ $log->written_input }}</span>
                    @if($log->written_input !== $log->prepared_written_input)
                    <div class="ml-6 text-sm text-grey-dark">{{ $log->prepared_written_input }}</div>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="border-b"></div>
        @endforeach
    </div>
@endsection

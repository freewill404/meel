@extends('layout.base-template', [
    'title'       => 'Register an account | Meel.me',
    'description' => 'Register today to start mailing yourself',
])

@section('content')

    <div class="max-w-sm mx-auto mb-8">
        @include('layout.header', ['title' => 'Register'])
    </div>

    <form class="panel max-w-xs mx-auto" method="post" action="{{ route('register.post') }}">
        {{ csrf_field() }}

        <label>
            Email
            <input class="field" type="email" name="email" value="{{ old('email') }}" required autofocus>
        </label>

        <label>
            Password
            <input class="field" type="password" name="password" required>
        </label>

        <label class="block">
            Timezone
            <select name="timezone" class="field" id="tz-select">
                @foreach($timezones as $region => $list)
                    <optgroup label="{{ $region }}">
                        @foreach($list as $timezone => $name)
                            <option value="{{ $timezone }}">{{ $name }}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
        </label>

        <button type="submit" class="btn block ml-auto mt-4">Create account</button>
    </form>


    @if($errors->count())
        <div class="max-w-sm mx-auto text-sm p-2 mt-8 bg-red-lighter rounded border-l-4 border-red">
            <strong class="block mb-2">The following errors occurred:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

@endsection


@push('footer')
    <script>
        var tzName = '{{ old('timezone') }}' || jstz.determine().name();

        var el = document.getElementById('tz-select');

        for (var i = 0; i < el.options.length; i++) {
            if (el.options[i].value === tzName) {
                el.options[i].selected = true;

                break;
            }
        }
    </script>
@endpush

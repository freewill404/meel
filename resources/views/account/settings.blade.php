@extends('layout.base-template', [
    'title' => 'Settings | Meel.me',
])

@section('content')

    @include('layout.header', ['title' => 'Settings'])

    <div class="max-w-md mx-auto mt-8 mb-16">

        @if(Session::has('setting-status'))
            <div class="p-2 mb-8 border-l-4 border-green">
                {{ Session::get('setting-status') }}
            </div>
        @endif


        <form class="max-w-xs" method="post" action="{{ route('user.account.settings.updateTimezone') }}">
            {{ csrf_field() }}

            <label class="block">
                Timezone
                <select name="timezone" class="field">
                    @foreach($timezones as $region => $list)
                        <optgroup label="{{ $region }}">
                            @foreach($list as $timezone => $name)
                                <option value="{{ $timezone }}" {{ $timezone === $user->timezone ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </label>

            <button class="btn">Update timezone</button>
        </form>

        <hr class="border-b my-8">

        <form class="max-w-xs" method="post" action="{{ route('user.account.settings.updatePassword') }}">
            {{ csrf_field() }}

            <label>
                New password
                <input class="field" type="password" name="new_password" required>
            </label>

            <label>
                Repeat new password
                <input class="field" type="password" name="new_password_confirmation" required>
            </label>

            <p class="mb-2">
                All other logged in devices will be logged out after changing your password.
            </p>

            <button class="btn">Change password</button>

        </form>

        @if($errors->count())
            <div class="mx-auto text-md p-2 mt-8 bg-red-lighter rounded border-l-4 border-red">
                <strong class="block mb-2">The following errors occurred:</strong>
                <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                </ul>
            </div>
        @endif

    </div>

@endsection

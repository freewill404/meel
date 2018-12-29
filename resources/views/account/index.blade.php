@extends('layout.base-template', [
    'title' => 'Settings',
])

@section('content')
    @include('layout.header', ['title' => 'Settings', 'maxWidth' => 'max-w-md'])

    <div class="max-w-lg mx-auto mt-8 mb-32">

        @if(Session::has('setting-status'))
            <div class="p-2 mb-8 border-l-4 border-green">
                {{ Session::get('setting-status') }}
            </div>
        @endif

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


        <div class="border-b mb-8"></div>


        <div class="flex flex-col sm:flex-row">
            <div class="w-48 mr-0 sm:mr-16">
                <div class="text-lg font-semibold mb-2">Emails</div>

                <p class="text-sm">
                    You need emails to email yourself.
                    You'll receive a notification when you are starting to run out.
                </p>
            </div>

            <div class="sm:mt-0 mt-8">
                <p class="text-xl">
                    You have <strong>{{ $user->emails_left }}</strong> emails left
                </p>

                <br>

                <a class="text-black text-sm font-semibold" href="{{ route('user.more') }}">Buy more emails</a>
            </div>
        </div>


        <div class="border-b my-8"></div>


        <div class="flex flex-col sm:flex-row">
            <div class="w-48 mr-0 sm:mr-16">
                <div class="text-lg font-semibold mb-2">Timezone</div>

                <p class="text-sm">
                    Having the correct timezone set ensures you receive your emails on time.
                </p>
            </div>

            <div class="max-w-sm sm:mt-0 mt-8">
                <form class="" method="post" action="{{ route('user.account.settings.updateTimezone') }}">
                    {{ csrf_field() }}

                    <select name="timezone" class="field">
                        @foreach($timezones as $region => $list)
                            <optgroup label="{{ $region }}">
                                @foreach($list as $timezone => $name)
                                    <option value="{{ $timezone }}" {{ $timezone === $user->timezone && ! isset($found) ? $found = 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>

                    <button class="btn-setting">Update timezone</button>
                </form>
            </div>
        </div>


        <div class="border-b my-8"></div>



        <div class="flex flex-col sm:flex-row">
            <div class="w-48 mr-0 sm:mr-16">
                <div class="text-lg font-semibold mb-2">Password</div>

                <p class="text-sm">
                    All other logged-in devices will be logged out after changing your password.
                </p>
            </div>

            <div class="w-64 sm:mt-0 mt-8">
                <form class="" method="post" action="{{ route('user.account.settings.updatePassword') }}">
                    {{ csrf_field() }}

                    <label class="text-sm mt-2">
                        New password
                        <input minlength="6" class="field" type="password" name="new_password" required>
                    </label>

                    <label class="text-sm mt-2">
                        Repeat new password
                        <input minlength="6" class="field" type="password" name="new_password_confirmation" required>
                    </label>

                    <button class="btn-setting">Change password</button>
                </form>
            </div>
        </div>


        <div class="border-b my-8"></div>


        <div class="flex flex-col sm:flex-row">
            <div class="w-48 mr-0 sm:mr-16">
                <div class="text-lg font-semibold mb-2">Account</div>
            </div>

            <div class="sm:mt-0 mt-8">
                <form method="post" action="{{ route('logout') }}">
                    {{ csrf_field() }}
                    <button class="btn-setting">Logout</button>
                </form>
            </div>
        </div>

    </div>
@endsection

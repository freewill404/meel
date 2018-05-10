@extends('layout.base-template', [
    'title'       => 'Meel.me | Profile',
    'description' => 'SEO description',
])

@section('content')

    <div  class="max-w-sm mx-auto mt-2">

        <div class="flex justify-between items-center">
            <h1>Profile</h1>

            <div class="text-sm">
                <a href="{{ route('profile') }}">profile</a> &nbsp;|&nbsp; <a href="{{ route('login') }}">logout</a>
            </div>
        </div>



    </div>

@endsection

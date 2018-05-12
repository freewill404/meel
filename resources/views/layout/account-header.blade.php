<div class="border-b">
    <div class="flex justify-between items-center max-w-lg mx-auto p-2">

        <a class="text-black" href="/">Meel.me</a>

        <div>
            <a class="mr-4 text-black" href="{{ route('account') }}">account</a>

            <form method="post" class="inline-block" action="{{ route('logout') }}">
                {{ csrf_field() }}
                <button type="submit">logout</button>
            </form>

        </div>
    </div>
</div>

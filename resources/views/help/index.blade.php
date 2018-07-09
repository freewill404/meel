@extends('layout.base-template', [
    'title'       => 'Help | Meel.me',
    'description' => 'Need help emailing yourself? You\'ve come to the right place.',
])

@section('content')

    <div class="max-w-md mx-auto mt-2 mb-16">

        @include('layout.header', ['title' => 'Help'])

        <p class="text-md mt-8 sm:ml-4">
            Need help emailing yourself?
            This page should give you enough information to use the site.
        </p>

        <h2 class="mt-8">When formats</h2>
        <p class="sm:ml-4">
            The format used for the "when" field is split up into three parts: recurring, relative and absolute.
            Each part is described in more detail below.
            <br>
            <br>
            When formats are finickity, make sure you double-check the way the website interprets the written input before saving your email schedule.
        </p>


        <h3 class="mt-8">Recurring when formats</h3>
        <div class="sm:ml-4">
            <p>
                Recurring email schedules allow you to keep your life on track.
                Create email schedules for the future you and hope that you'll actually listen to them.
                <br><br>
                The following recurring formats are available:
            </p>
            <ul>
                <li>Daily</li>
                <li>Weekly</li>
                <li>Monthly</li>
                <li>Every Monday, Thursday and Sunday</li>
                <li>Every third Saturday of the month</li>
                <li>Every last Saturday of the month</li>
                <li>Every 3 months</li>
                <li>Every 4 weeks on Saturday</li>
                <li>Yearly</li>
            </ul>
            <p class="mt-4">
                Recurring formats can be combined with absolute formats:
            </p>
            <ul>
                <li>Monthly on Wednesday at 22:00</li>
                <li>Monthly on the 15th at 22:00</li>
            </ul>
        </div>


        <h3 class="mt-8">Absolute when formats</h3>
        <div class="sm:ml-4">
            <p>
                Absolute formats are not based on the current time, they are absolute.
                An absolute format is either a time or a date.
                <br><br>
                Absolute dates are pretty straight-forward, they try their best to figure out which number is the month and which number is the date.
                If the date and month are ambiguous, the sensible European standard is assumed (dd-mm-yyyy).
                <br><br>
                Below are a few examples of absolute dates:
            </p>
            <ul>
                <li>1-12</li>
                <li>12-31</li>
                <li>01-01-2018</li>
            </ul>

            <p class="mt-4">
                Absolute times work like this:
            </p>
            <ul>
                <li>22:00</li>
                <li>at 9</li>
            </ul>
        </div>


        <h3 class="mt-8">Relative when formats</h3>
        <div class="sm:ml-4">
            <p>
                Relative when formats are relative to the current time.
                In some cases they can be combined with absolute formats.
                They can not be combined with recurring formats because that does not make sense.
                <br><br>
                The following formats, and simple variations of them, are supported:
            </p>
            <ul>
                <li>now</li>
                <li>tomorrow</li>
                <li>in 1 minute / hour</li>
                <li>1 minute / hour from now</li>
                <li>in 1 day / week / month / year</li>
                <li>1 day / week / month / year from now</li>
                <li>next week / month / year</li>
            </ul>

            <p class="mt-4">
                Relative formats can also be combined:
            </p>
            <ul>
                <li>in 1 hour and 30 minutes</li>
                <li>1 week and 3 day from now</li>
            </ul>

            <p class="mt-4">
                Some relative formats can be combined with absolute formats:
            </p>
            <ul>
                <li>tomorrow at 22:00</li>
            </ul>
        </div>


        <h2 class="mt-8">What formats</h2>
        <p class="sm:ml-4">
            You can add formats to the what text of your email schedules.
            The formats are replaced with dynamic values when the emails are sent.
            The available formats are listed below.
        </p>


        <h4 class="mt-8">%t - Times sent</h4>
        <div class="sm:ml-4">
            <p>
                Adding "%t" to a what text will get replaced with the times an email has been sent (including the current email).
                The value can be offset by suffixing +(number) or -(number).
                <br><br>
                Examples:
            </p>
            <ul>
                <li>Times sent: %t</li>
                <li>Weeks until launch: %t-5</li>
                <li>Rebecca's birthday (%t+20 years old)</li>
            </ul>
        </div>


        <h4 class="mt-8">%a - Days since schedule created</h4>
        <div class="sm:ml-4">
            <p>
                Adding "%a" to a what text will get replaced with the amount of days ago the email schedule was created.
                The value can be offset by suffixing +(number) or -(number).
                <br><br>
                Examples:
            </p>
            <ul>
                <li>Days since email created: %a</li>
                <li>Days sober: %a+5</li>
            </ul>
        </div>


        <h4 class="mt-8">%d - Days since email was last sent</h4>
        <div class="sm:ml-4">
            <p>
                Adding "%d" to a what text will get replaced with the amount of days since the email was last sent.
                The value can be offset by suffixing +(number) or -(number).
                <br><br>
                Examples:
            </p>
            <ul>
                <li>Days since previous email: %d</li>
                <li>Last cleaned my house %d days ago</li>
            </ul>
        </div>


        <h4 class="mt-8">%f[X] - Current date format</h4>
        <div class="sm:ml-4">
            <p>
                Adding "%f[X]" to a what text will format the current datetime, with X being used as the <a class="underline" href="http://php.net/manual/en/function.date.php" rel="nofollow" target="_blank">php date format</a>.
                <br><br>
                Examples, the formatted result is contained in (braces).
            </p>
            <ul>
                <li>Review week %f[W]/52 <i>(Review week 13/52)</i></li>
                <li>This %f[l] <i>(this Saturday)</i></li>
                <li>%f[d-m-Y H:i:s] <i>(28-03-2018 12:30:00)</i></li>
                <li>Days left this year %f[z]/365 <i>(Days left this year 58/365)</i></li>
            </ul>
        </div>

    </div>

@endsection

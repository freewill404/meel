@extends('layout.base-template', [
    'title' => 'More emails | Meel.me',
])

@section('content')

    @include('layout.header', ['title' => 'More emails', 'maxWidth' => 'max-w-lg'])

    <div class="max-w-md mx-auto mt-8 mb-16">

        <p class="text-lg">
            You currently have <strong>{{ $user->emails_left }}</strong> emails left.
        </p>

        <p class="mt-8">
            Once you run out of emails, email schedules will still run, but no emails will be sent.
            You will be automatically notified when you are running out of emails.
        </p>

        <h2 class="mt-8">More emails</h2>
        <p>
            More emails can be purchased using PayPal.
            You will receive the emails within one day of the transaction.
        </p>

        <form class="panel mt-8" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
            <input type="hidden" name="cmd" value="_s-xclick">
            <table>
                <tr>
                    <td>
                        <input type="hidden" name="on0" value="Emails">
                        <strong>Buy more emails</strong>
                    </td>
                </tr>
                <tr>
                    <td>
                        <select class="field pointer" name="os0">
                            <option value="100 emails">100 emails -  €3,00 EUR</option>
                            <option value="400 emails">400 emails - €10,00 EUR</option>
                            <option value="1000 emails">1000 emails -  €25,00 EUR</option>
                            <option value="5000 emails">5000 emails - €100,00 EUR</option>
                        </select>
                    </td>
                </tr>
            </table>
            <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----">
            <input type="image" src="https://www.paypalobjects.com/en_US/NL/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
            <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
        </form>

        <h2 class="mt-16">Cryptocurrency</h2>
        <p>
            Emails can also be purchased using cryptocurrency (bitcoin, ethereum, etc...), <a class="font-bold" href="{{ route('user.feedback') }}">send me a message</a> if you would like to do that.
        </p>

    </div>

@endsection

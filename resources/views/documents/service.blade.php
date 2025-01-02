@extends('layouts.app')

@section('title', 'Terms of Service')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header">{{ config('app.name') }} Terms of Service</div>
        <div class="card-body">
            <p>{{ config('app.name') }} has some very basic terms of service that we expect you to follow on all parts of the {{ config('app.name') }} Community - be it the Community Discord server, in-game, the in-game chat, on-site, or the forums.</p>
            <p>If you fail to follow the {{ config('app.name') }} terms of service, we reserve the right to suspend your account or delete any content that violates the terms of service. The {{ config('app.name') }} Terms of Service may change at any time.</p>
            <p>By using, accessing, creating an account, or otherwise interacting with any part of {{ config('app.name') }}, you automatically agree to the following:</p>
            <ol>
            <li>That you have read, agreed to, and promise to comply with these terms</li>
                <li>That you have read, and agreed to the <a href="{{ route('document', 'rules') }}">Rules</a> for whatever part of {{ config('app.name') }} you are interacting with</li>
                <li>That you acknowledge we, and all other {{ config('app.name') }} staff reserve the right to suspend your account, or otherwise bar you from any {{ config('app.name') }} service at any time</li>
                <li>That you acknowledge the {{ config('app.name') }} Terms of Service, and rulebook, may change at any time without prior notice</li>
                <li>That you acknowledge <b>you must be 13 years old or older</b> to use {{ config('app.name') }} and will face permanent suspension if you are not</li>
                <li>That you have read and acknowledged our <a href="{{ route('document', 'privacy') }}">Privacy Policy</a></li>
                <li>You may not spam the site, API, or any service with requests</li>
                <li>You may not upload, or distribute pornography, gore, illegal content on {{ config('app.name') }} or with using {{ config('app.name') }}. If you do so, we will report you to the proper authorities of the jurisdiction you live in</li>
                <li>You may not upload, or distribute your own, or anybody elses personal information on {{ config('app.name') }}</li>
            </ol>
            <p>We encourage you to read the <a href="{{ route('document', 'rules') }}">Rules</a> after this. Different parts of {{ config('app.name') }} have different rules, but the Terms of Service apply to every part of {{ config('app.name') }}.</p>
            <p>You may appeal any ban or punishment you receive by <a href="mailto:{{ config('app.mailing_address') }}">sending us an email</a>, unless it has been explicitly stated that your punishment is not appealable.</p>
        </div>
    </div>
</div>
@endsection

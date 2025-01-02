@extends('layouts.app')

@section('title', 'Privacy Policy')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header">{{ config('app.name') }} Privacy Policy</div>
        <div class="card-body">
            <p>{{ config('app.name') }} collects only the amount of data needed from the user in order to function properly. Below is the data that {{ config('app.name') }} collects in its database:</p>
            <ul>
                <li>Your email address</li>
                <li>Your password (hashed, never plaintext)</li>
                <li>Your IP address (register IP address and last used IP address)</li>
                <li>Invite key used to register</li>
            </ul>
            <p>{{ config('app.name') }} will never reveal any of your information on purpose. If you want your data removed from {{ config('app.name') }}, you may contact us <a href="mailto:{{ config('app.mailing_address') }}">by email</a> including where and what data you want removed. You may also have to prove that you are the owner, or creator, of that data.</p>
            <p>In the event that a data breach ever occurs, or user data is otherwise compromised, all users will be immediately notified by whatever is the fastest means (either through email, the community Discord server, or a banner on the site itself.) Additionally, if such data gets compromised, a fully transparent post-mortem of what was stolen will be published.</p>
            <p>Additionally, {{ config('app.name') }} uses advertisements to create money that hosts our servers. We use Google AdSense, and they collect cookies to show personalized advertisements. You may opt out of personalized advertisements by <a href="https://www.google.com/settings/ads">visiting your Google account's advertisement settings page.</a>
        </div>
    </div>
</div>
@endsection
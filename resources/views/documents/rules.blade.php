@extends('layouts.app')

@section('title', 'Rules')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header">{{ config('app.name') }} Rules</div>
        <div class="card-body">
            <h1>{{ config('app.name') }} Rules</h1>
            <p>Different parts of {{ config('app.name') }} have different rules, as we increase liability for certain parts of {{ config('app.name') }}.<br>However, every part of {{ config('app.name') }} also has the <a href="{{ route('document', 'service') }}">{{ config('app.name') }} Terms of Service</a> apply to it. You should read that first.</p>
            <h2>Games</h2>
            <p>Anything in-game; the chat, servers, and so on. {{ config('app.name') }} claims no responsibility for what happens inside of a server, and we do not log chats (and will never be logged by {{ config('app.name') }}.)</p>
            <ul>
                <li><b>Server hosters are fully liable for anything that occurs on their server</b> which violates the Terms of Service, unless they can prove to a reasonable degree that an exploiter was present, who caused it.</li>
                <li><b>Griefing (destroying others work) in building games or other games which employ building mechanics is not allowed</b>, unless the server hoster explicitly allows it.</li>
                <li><b>Cheating, exploiting, or hacking is not allowed</b>, unless the server hoster explicitly allows it.</li>
                <li><b>We encourage you to respect others.</b> This is a video game.</li>
                <li><b>You may not say slurs of any kind in a demeaning or derogatory way.</b></li>
                <li><b>Do not post or say erotic, or otherwise NSFW content.</b> Be mature.</li>
            </ul>
            <h2>Website</h2>
            <p>This applies to everything that occurs onsite. This includes all uploaded content, forum posts, usernames, user biographies, asset names, etc.</p>
            <ul>
                <li><b>You may not say slurs of any kind in a demeaning or derogatory way.</b></li>
                <li><b>Do not post or say erotic, or otherwise NSFW content.</b> Be mature.</li>
                <li><b>Do not create an excessive amount of accounts</b> to namesnipe, or farm Tokens. We allow alternate accounts to a reasonable degree.</li>
                <li><b>We encourage you to invite rational, mature people</b>, but whatever rule they break, you are held liable.</li>
            </ul>
            <h2>Discord</h2>
            <p>These are the rules that apply to the {{ config('app.name') }} Discord server.</p>
            <ul>
                <li><b>All rules that apply to the {{ config('app.name') }} website</b> also apply to the {{ config('app.name') }} Discord server.</li>
                <li><b>You may not join unless you have an account.</b> This rule isn't particularly enforced, but don't expect to get verified if you don't have one.</li>
                <li><b>You may not mass ping or spam messages.</b></li>
                <li><b>We have a 3-warning system</b> - for each time you break a rule, you receive one warning. After you receive above three warnings, you will be permanently banned.</li>
            </ul>
            <p>The rules seem long, because we have to be specific, but in short we really just ask you to not be stupid and to be respectful. Help us keep {{ config('app.name') }} a civil and nice place to be in.</p>
        </div>
    </div>
</div>
@endsection

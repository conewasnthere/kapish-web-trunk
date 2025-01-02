@extends('layouts.app')

@section('title', 'Contributors')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header">{{ config('app.name') }} Credits</div>
        <div class="card-body">
            <h1>Tadah Developers</h1>
            <ul>
                <li><b>kinery</b> - Project Lead</li>
                <li><b>spike</b> - Tadah lead artist, designed the logo and Token icon</li>
                <li><b>taskmanager</b> - Frontend development</li>
                <li><b>Iago</b> - Client and frontend development</li>
                <li><b>hitius</b> - Dedicated servers</li>
                <li><b>Carrot</b> - Backend engineer</li>
                <li><b>pizzaboxer</b> - Client development</li>
                <li><b>Ahead</b> - Backend development</li>
            </ul>
            <h4>Special thanks</h4>
            <ul>
                <li><b>Anonymous</b> - Helped clean up code, client help</li>
                <li><b>splat</b> - Ideas guy and helped found Tadah</li>
                <li><b>past</b> - Catalog upload, event staff</li>
                <li><b>warden</b> - Catalog uploader, event host</li>
                <li><b>cole</b> - Main catalog manager</li>
                <li><b>You</b> - for using Tadah!</li>
            </ul>
            <h1>Kapish Developers</h1>
            <ul>
                <li><b>Stan</b> - Project Lead</li>
            </ul>

            <p>Without these people lending their help, {{ config('app.name') }} would not be as good as it is today. Thanks everyone.</p>
        </div>
    </div>
</div>
@endsection

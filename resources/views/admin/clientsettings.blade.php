@inject('user', 'App\Http\Controllers\UsersController')

@extends('layouts.admin')

@section('title', 'Manage ClientSettings')

@section('content')
<div class="container">
    <h1><b>Manage Client FFlags</b></h1>
    <p>Toggle specific FFlags for use on 2016, 2014 and 2012. Don't mess around if you don't know what you're doing.</p>    
    <hr>
    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session()->get('error') }}
        </div>
    @endif
    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif
    <form method="POST" action="{{ route('admin.togglefflag') }}">
        @csrf

        <div class="form-group">
            <label for="name">Flag</label>
            <input type="text" name="fflag" class="form-control" id="fflag" placeholder="example: FFlagDebugCrashEnabled">
        </div>

        <div class="form-group">
            <label for="version">Version</label>
            <select class="form-control" id="version" name="version" required>
                <option>2012</option>
                <option>2014</option>
                <option>2016</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success shadow-sm">Toggle Flag</button>
    </form>
    <hr>
    <h1><b>Current FFlags</b></h1>
    <li><a href="{{ route('admin.clientsettings', ['version' => '2012']) }}">2012</a></li>
    <li><a href="{{ route('admin.clientsettings', ['version' => '2014']) }}">2014</a></li>
    <li><a href="{{ route('admin.clientsettings', ['version' => '2016']) }}">2016</a></li>
    <hr>
    @foreach($fflags->keys() as $fflag)
    <div>
        <code>{{$fflag}}: </code><span class="{{ $fflags->get($fflag) == 'True' ? 'text-success' : 'text-danger' }}">{{$fflags->get($fflag)}}</span>
    </div>
    @endforeach
</div>
@endsection

@section('scripts')
<script>
    $('#robloxItemInfo').click(function(event) {
        $.ajax({
            type: "GET",
            url: "/admin/robloxitemdata/" + $('#robloxid').val(),
            dataType: "json",
            success: function (data) {
                $("#itemname").val(data["Name"]);
                $("#description").text(data["Description"]);
                $("#thumbnailurl").val("https://www.roblox.com/Thumbs/Asset.ashx?width=420&height=420&assetId=" + $('#robloxid').val());
            }
        });

        $.ajax({
            type: "GET",
            url: "/admin/robloxxmldata/" + $('#robloxid').val() + "/" + $('#robloxversion').val(),
            success: function (data){
                $("#xml").html(data.replaceAll("http://www.roblox.com/asset", "https://assetdelivery.roblox.com/v1/asset").replaceAll("class=\"Accessory\"", "class=\"Hat\""));
            }
        });
    });
</script>
@endsection
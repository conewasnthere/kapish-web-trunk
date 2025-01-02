@extends('layouts.app')

@section('title', 'Settings')

@section('content')
<div class="container">
    <h1 class="font-weight-bold">User Settings</h1>
    <hr>

    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session()->get('error') }}
        </div>
    @endif

    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif

    <div class="card rounded-0 mb-3 shadow-sm">
        <div class="card-body py-0 px-0">
            <ul class="nav nav-pills nav-fill" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link rounded-0 active" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="true">Profile</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link rounded-0" id="pills-account-tab" data-toggle="pill" href="#pills-account" role="tab" aria-controls="pills-account" aria-selected="false">Account</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link rounded-0" id="pills-theme-tab" data-toggle="pill" href="#pills-theme" role="tab" aria-controls="pills-theme" aria-selected="false">Theme</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link rounded-0" id="pills-preferences-tab" data-toggle="pill" href="#pills-preferences" role="tab" aria-controls="pills-preferences" aria-selected="false">Preferences</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
            <div class="container px-0">
                <div class="card card-body">
                    <form method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="blurb">
                                <h3 class="mb-0">Blurb</h2>
                                <small class="text-muted">Maximum 700 characters</small>
                            </label>
                            <textarea name="blurb" class="form-control @error('blurb') is-invalid @enderror" id="blurb" rows="3">{{ Auth::user()->blurb }}</textarea>

                            @error('blurb')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="text-right px-0">
                            <button type="submit" class="btn btn-primary shadow-sm"><i class="far fa-save mr-1"></i>Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="tab-pane fade" id="pills-account" role="tabpanel" aria-labelledby="pills-account-tab">
            <div class="container px-0">
                <div class="card card-body">
                    <h3>Account Information</h2>
                    <p class="mb-0">Username: </p>
                    <p class="text-muted">{{ Auth::user()->username }}</p>
                    <p class="mb-0">Email: </p>
                    <p class="text-muted">{{ Auth::user()->email }}</p>
                    <hr>
                    <h3 class="mb-0">Discord Link</h3>
                    @if (config('app.discord_verification_required'))<small class="text-muted mb-1">Required to play games.</small>@endif
                    @if (Auth::user()->discordLinked())
                        <p>Discord ID: {{ Auth::user()->discord_id }}</p>
                    @else
                        <p><a class="btn btn-sm btn-primary" href="{{ route('my.discordlink') }}">Link Discord</a></p>
                    @endif
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="pills-preferences" role="tabpanel" aria-labelledby="pills-preferences-tab">
            <div class="container px-0">
                <div class="card card-body">
                    <h3>Client Settings</h2>
                    <small class="text-muted pb-2">Currently only apply for 2014.</small>
                    <form method="post">
                        @csrf
                        <div class="form-check">
                            <input class="form-check-input" value="old_cores" type="radio" name="old_cores" id="themeRadio" {{(Auth::user()->old_cores ? '' : 'checked')}}>
                            <label class="form-check-label" for="themeRadio">
                                New CoreScripts
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" value="new_cores" type="radio" name="old_cores" id="themeRadio" {{(Auth::user()->old_cores ? "checked" : '')}}>
                            <label class="form-check-label" for="themeRadio">
                                Old CoreScripts
                            </label>
                        </div>
                        <div class="text-right px-0">
                            <button type="submit" class="btn btn-primary shadow-sm"><i class="far fa-save mr-1"></i>Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="pills-theme" role="tabpanel" aria-labelledby="pills-theme-tab">
            <div class="container px-0">
                <div class="card card-body">
                    <h3>Site Theme</h2>
                    <form method="post">
                        @csrf
                        @foreach(config('app.themes') as $theme)
                            <div class="form-check">
                                <input class="form-check-input" value="{{$theme}}" type="radio" name="theme" id="themeRadio" {{ (Cookie::get('theme') == $theme) ? "checked" : "" }}>
                                <label class="form-check-label" for="themeRadio">
                                    {{ ($theme != 'lumendark') ? Str::ucfirst($theme) : 'Lumen Dark' }}
                                </label>
                            </div>
                        @endforeach
                        <div class="text-right px-0">
                            <button type="submit" class="btn btn-primary shadow-sm"><i class="far fa-save mr-1"></i>Save</button>
                        </div>
                    </form>                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>

</script>
@endsection

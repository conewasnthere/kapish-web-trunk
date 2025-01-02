@extends('layouts.app')

@section('title', 'Configure Server')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">{{ __('Configure Server') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('servers.processconfigure', $server->uuid) }}" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Server Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $server->name }}" required autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="description" class="col-md-4 col-form-label text-md-right">{{ __('Description') }}</label>

                            <div class="col-md-6">
                                <textarea id="description" type="text" class="form-control @error('description') is-invalid @enderror" name="description" required>{{ $server->description }}</textarea>

                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="version" class="col-md-4 col-form-label text-md-right">{{ __('Version') }}</label>

                            <div class="col-md-6">
                                <select class="form-control @error('version') is-invalid @enderror" id="version" name="version" required>
                                    @foreach (config('app.clients') as $client => $version)
                                        <option {{ ($server->version == $client) ? 'selected' : '' }}>{{ $client }}</option>
                                    @endforeach
                                </select>
                                @error('version')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
						

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input {{ $server->unlisted ? 'active' : '' }}" type="checkbox" name="unlisted" id="unlisted" {{ $server->unlisted ? 'checked' : '' }}>

                                    <label class="form-check-label {{ $server->unlisted ? 'active' : '' }}" for="unlisted">
                                        {{ __('Unlisted') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input {{ $server->allow_guests ? 'active' : '' }}" type="checkbox" name="guest" id="guest" {{ $server->allow_guests ? 'checked' : '' }}>

                                    <label class="form-check-label {{ $server->allow_guests ? 'active' : '' }}" for="guest">
                                        {{ __('Allow Guests (Insecure)') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="maxplayers" class="col-md-4 col-form-label text-md-right">{{ __('Max Players') }}</label>

                            <div class="col-md-6">
                                <input id="maxplayers" type="number" onwheel="this.blur()" class="form-control" name="maxplayers" value="{{ $server->maxplayers }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="chattype" class="col-md-4 col-form-label text-md-right">{{ __('Chat Type') }}</label>

                            <div class="col-md-6">
                                <select class="form-control @error('chattype') is-invalid @enderror" id="chattype" name="chattype" required>
                                    <option value="0" {{($server->chat_type == 0 ? 'selected' : '')}}>Classic</option>
                                    <option value="1" {{($server->chat_type == 1 ? 'selected' : '')}}>Bubble</option>
                                    <option value="2" {{($server->chat_type == 2 ? 'selected' : '')}}>Classic and Bubble</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="place" class="col-md-4 col-form-label text-md-right @error('place') is-invalid @enderror">{{ __('Place') }}</label>

                            <div class="col-md-6">
                                <input type="file" class="form-control-file @error('place') is-invalid @enderror" name="place">

                                @error('place')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary shadow-sm">
                                    Save Changes
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

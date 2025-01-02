@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Queue Confirmation') }}</div>

                <div class="card-body text-center">
                    <p>{{ __('Thank you for joining the queue for the new Kapish 2020 client!') }}</p>
                    <p>{{ __('We will provide you with a role if you are randomly selected.') }}</p>
                    <p>{{ __('Please note that access to the new client is limited and participants will be picked randomly from the queue.') }}</p>
                    <div>
                        <a href="/"><button class="btn btn-primary">{{ __('Go to Homepage') }}</button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

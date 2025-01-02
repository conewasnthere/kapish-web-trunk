@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('New Kapish 2020 Client') }}</div>

                <div class="card-body text-center">
                    <p>{{ __('The new Kapish 2020 client is coming soon!') }}</p>
                    <p>{{ __('With new features and improvements, the new client will be a major update for our users.') }}</p>
                    <p>{{ __('To join the queue and be one of the first to try the new client, click the button below.') }}</p>
                    <p>{{ __('Please note that access to the new client is limited and participants will be picked randomly from the queue.') }}</p>
                    <div>
                        <a href="/waitlist-lolimin"><button class="btn btn-primary">{{ __('Join the Queue') }}</button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

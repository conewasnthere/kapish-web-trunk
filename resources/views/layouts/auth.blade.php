<x-app-layout :title="$title" fluff="off">
    @isset ($head)
        <x-slot:head>
            {{ $head }}
        </x-slot>
    @endisset

    <main class="flex-shrink-0 d-flex align-items-center min-vh-100">
        <div class="container vw-100">
            @if (session('status'))
                <div class="alert alert-success alert-dismissible mt-3 mb-5 shadow-sm fade show" role="alert">
                    @if (session('status') == 'verification-link-sent')
                        {{ __('Re-sent verification email! Please check your inbox.') }}
                    @else
                        {!! session('status') !!}
                    @endif

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('Close') }}"></button>
                </div>
            @endif

            <div class="my-3 d-flex flex-column align-items-center">
                <div class="mb-3">
                    <a href="/"><img src="{{ asset('img/logo/small.png') }}" width="100"></a>
                </div>

                <div @class(['card border-0 shadow rounded-4', $width])>
                    <div class="card-body p-4">
                        {{ $slot }}
                    </div>
                </div>

                @isset ($bottom)
                    {{ $bottom }}
                @else
                    <div class="mt-3">
                        @include ('partials.language-dropdown')
                    </div>
                @endisset
            </div>
        </div>
    </main>
</x-app-layout>

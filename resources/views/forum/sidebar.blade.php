<div class="col col-md-2 mb-3 my-md-0">
    <div class="list-group">
        @if (Auth::check())
            <div class="border-bottom pb-2 d-flex justify-content-md-start justify-content-center">
                <img class="position-relative img-fluid rounded-circle headshot-bg" style="max-height: 2.5rem;" data-tadah-thumbnail-type="user-headshot" data-tadah-thumbnail-id="{{ Auth::user()->id }}" src="{{ asset('images/thumbnail/blank.png') }}">
                <div class="d-inline-block align-middle mx-2">
                    <div class="font-weight-bold mb-0">                           
                        <h5 class="font-weight-bold mb-0">{{Auth::user()->username}}</h5>
                    </div>
                    <div class="d-inline-flex">
                        <span class="text-muted mb-0">
							{{ Auth::user()->getPostsString() }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center w-100 my-2">
                @if (Auth::check())
                    @if (isset($category) && !$category->admin_only)
                            <div class="mb-0 w-100">
                                <a class="w-100 btn btn-success shadow-sm" href="{{ route('forum.createthread', $category->id) }}"><i class="fas fa-plus mr-1" aria-hidden="true"></i>New Post</a>
                            </div>
                    @else
                        @if (isset($category) && Auth::user()->isAdmin())
                            <div class="mb-0 w-100">
                                <a class="btn w-100 btn-success shadow-sm" href="{{ route('forum.createthread', $category->id) }}"><i class="fas fa-plus mr-1" aria-hidden="true"></i>New Post</a>
                            </div>
                        @endif
                    @endif
                @endif
            </div>
        @endif
        <div class="text-muted">Forum Categories</div>
        @foreach ($categories as $cate)
        <a href="{{route('forum.category', $cate->id)}}" class="text-decoration-none {{ Request::segment(2) == $cate->id ? 'font-weight-bold' : 'font-weight-normal'}}">                        
            {{$cate->name}}
        </a>
        @endforeach
    </div>
</div>
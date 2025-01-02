<div id="loading" class="col d-none justify-content-center">
    <div class="spinner-border d-block" style="width: 3rem; height: 3rem" role="status">                                
        <div class="sr-only">Loading</div>
    </div>
</div>

<div id="items" class="row mx-auto">
    @if($items->count() <= 0)
        <div id="empty" class="col text-center">
            <br>
            <img src="/images/blobs/exhausted.png" class="img-fluid">
            <h2>Nothing found</h2>
            <p>No items found. Perhaps consider <a href="/catalog">buying some.</a></p>
        </div>
    @endif
    @if($items->count() > 0)
        @foreach($items as $item)
        <div class="col-6 col-sm-4 col-md-2 p-2">
            <div class="card mb-2 border-0 text-center" style="width: 100%; height: 175px">
                <img class="card-img-top" style="object-fit: contain;" src="{{ asset('images/thumbnail/blank.png') }}" data-tadah-thumbnail-type="item-thumbnail" data-tadah-thumbnail-id="{{ $item->id }}" width="100" height="100" alt="{{$item->name}} thumbnail">
                <div class="card-body p-1" style="display: block;">
                    <div class="text-truncate"><a href="/item/{{$item->id}}">{{$item->name}}</a></div>
                    <a id="item-wear" onclick="wear({{$item->id}})" <button type="submit" style="width: 100%" class="btn btn-sm btn-{{ ($item->wearing) ? "danger" : "primary" }} shadow-sm">{{ ($item->unequippable) ? "nope" : (($item->wearing) ? "Remove" : "Equip") }}</a>                    
                </div>
            </div>
        </div>
        @endforeach
    @endif
</div>

@if($items instanceof \Illuminate\Pagination\LengthAwarePaginator)
    <div class="d-flex justify-content-center">
        {{$items->links('pagination::bootstrap-4')}}
    </div>
@endif
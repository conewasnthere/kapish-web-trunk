<head>
    <script>
        function insert(id)
        {
            window.external.Insert("{{config('app.url')}}/asset?id=" + id);
        }
    </script>
</head>

<div style="color: #343434; font: normal normal bold 12px Arial" class="container">
    <div style="text-align: center;">
        This is a sad excuse for a toolbox because I'm lazy. - Iago
    </div>    
    <form method="get">
        <div class="input-group">
            <input style="width: 100%;" placeholder="Search" type="text" name="search"></input>
        </div>
    </form>
    <div style="width: 100%">
        @foreach($models as $model)
        <div style="margin: 3px; display: inline-block; float: left; border: 1px solid #ccc; width: 62px; height: 60px;">
            <button style="background: none; color: inherit; border: none; font: inherit; cursor: pointer; padding: 0px; outline: inherit;" onclick="insert({{$model->id}})">
                <img src="{{ route('item.thumbnail', $model->id) }}" title="{{$model->name}}" alt="{{ $model->name }} {{ __('thumbnail') }}" width="62">
            </button>
        </div>
        @endforeach
    </div>
</div>
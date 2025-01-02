@inject('thumbnail', \App\Http\Cdn\Thumbnail::class)
@extends('layouts.app')

@section('title', 'Character')

@section('content')
    <div class="modal fade" id="colorPicker" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table-color text-center">
                        <tbody>
                            <tr>
                                @foreach($codes as $color)
                                    <td data-toggle="tooltip" data-placement="top" title="{{ $color['name'] }}" onclick="sendColorRequest({{ $color['id'] }})" class="color rounded-circle shadow-lg border m-1" style="background-color: rgb({{ $color['rgb'] }}); color: rgb({{ $color['rgb'] }}); display: inline-block; height: 45px; cursor: pointer; width: 45px; border-color: #fff; border-style: solid; border-width: 2px;"> </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="colorPicker2" tabindex="-1" role="dialog" aria-labelledby="colorPickerLbl">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="colorPickerLbl">Choose color</h4>
                </div>
                <div class="modal-body">
                    <div class="back-color">
                        <h3 class="h3-color">Select a color</h3>
                        <table class="table-color">
                            <tbody>
                                <tr>
                                    @foreach($codes as $color)
                                        <td data-toggle="tooltip" data-placement="left" title="{{ $color['name'] }}" onclick="sendColorRequest({{ $color['id'] }})" class="color rounded-circle" style="background-color: rgb({{ $color['rgb'] }}); color: rgb({{ $color['rgb'] }}); display: inline-block; height: 40px; width: 40px; border-color: #fff; border-style: solid; border-width: 2px;"> </td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

	<div class="container">
		<div class="row">
			<div class="col-md-4">
				<div class="card">
					<div class="card-header text-center">Character</div>
					<div class="card-body text-center">
                        <button id="regenerate-character" class="btn btn-primary btn-block shadow-sm @if (!config('app.character_regeneration')) disabled @endif" @if (!config('app.character_regeneration')) disabled @endif><i class="fas fa-redo mr-1"></i>Regenerate</button>
                        <div class="position-relative my-2" id="thumbnail-container">
                            <button class="position-absolute btn btn-outline-secondary disabled" disabled  id="toggle-character-3d" style="bottom: 5; right: 0;">3D</button>
                            <img width="250" height="250" class="img-fluid" data-tadah-thumbnail-type="user-thumbnail" data-tadah-thumbnail-id="{{ Auth::user()->id }}" src="{{ $thumbnail::static_image('blank.png') }}" alt="Character image" id="thumbnail">
                            <div class="d-none" id="three-dee-spinner">
                                <div class="text-center d-inline-flex align-items-center justify-content-center" style="height: 250px; width: 250px">
                                    <div class="spinner-border text-dark" role="status" style="width: 50px; height: 50px;">
                                        <span class="sr-only">Loading 3D Thumbnail...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
					</div>
				</div>
			</div>
			<div class="col-md-8">
				<div class="card mt-3 mt-md-0">
					<div class="card-header border-bottom-0">
						<ul class="nav border-bottom justify-content-center nav-tabs card-header-tabs">
							<li class="nav-item"><a id="category-link" href="#" class="nav-link{!! (!isset($type) ? ' active' : '') !!}">Body Colors</a></li>
							<li class="nav-item"><a id="category-link" href="#" class="nav-link{!! ($type == "Hat" ? ' active' : '') !!}">Hats</a></li>
							<li class="nav-item"><a id="category-link" href="#" class="nav-link{!! ($type == "T-Shirt" ? ' active' : '') !!}">T-Shirts</a></li>
							<li class="nav-item"><a id="category-link" href="#" class="nav-link{!! ($type == "Shirt" ? ' active' : '') !!}">Shirts</a></li>
							<li class="nav-item"><a id="category-link" href="#" class="nav-link{!! ($type == "Pants" ? ' active' : '') !!}">Pants</a></li>
							<li class="nav-item"><a id="category-link" href="#" class="nav-link{!! ($type == "Face" ? ' active' : '') !!}">Faces</a></li>
                            <li class="nav-item"><a id="category-link" href="#" class="nav-link{!! ($type == "Head" ? ' active' : '') !!}">Heads</a></li>
                            <li class="nav-item"><a id="category-link" href="#" class="nav-link{!! ($type == "Package" ? ' active' : '') !!}">Packages</a></li>
                            <li class="nav-item"><a id="category-link" href="#" class="nav-link{!! ($type == "Gear" ? ' active' : '') !!}">Gears</a></li>
						</ul>
					</div>
					<div id="item-card" data-type="bodycolors" class="card-body">
					</div>
				</div>
                <hr>
                <div class="card">
                    <div class="card-header">
                        Currently Wearing
                    </div>
					<div id="wearing-item-card" class="card-body">
					</div>
				</div>
			</div>
		</div>
	</div>

<template id="bodycolors">
    <div style="height:240px;width:194px;text-align:center;margin:0 auto;">
        <div style="position: relative; margin: 11px 4px; height: 1%;">
            <div style="position: absolute; left: 72px; top: 0px; cursor: pointer">
                <div class="ColorChooserRegion border border-secondary rounded" data-toggle="modal" id="head" data-target="#colorPicker" style="background-color:rgb({{ $codes[array_search(strval($colors->head_color), array_column($codes, 'id'))]['rgb']; }});height:44px;width:44px;"> </div>
            </div>
            <div style="position: absolute; left: 0px; top: 52px; cursor: pointer">
                <div class="ColorChooserRegion border border-secondary rounded" data-toggle="modal" id="leftarm" data-target="#colorPicker" style="background-color:rgb({{ $codes[array_search(strval($colors->left_arm_color), array_column($codes, 'id'))]['rgb']; }});height:88px;width:40px;"> </div>
            </div>
            <div style="position: absolute; left: 48px; top: 52px; cursor: pointer">
                <div class="ColorChooserRegion border border-secondary rounded" data-toggle="modal" id="torso" data-target="#colorPicker" style="background-color:rgb({{ $codes[array_search(strval($colors->torso_color), array_column($codes, 'id'))]['rgb']; }});height:88px;width:88px;"> </div>
            </div>
            <div style="position: absolute; left: 144px; top: 52px; cursor: pointer">
                <div class="ColorChooserRegion border border-secondary rounded" data-toggle="modal" id="rightarm" data-target="#colorPicker" style="background-color:rgb({{ $codes[array_search(strval($colors->right_arm_color), array_column($codes, 'id'))]['rgb']; }});height:88px;width:40px;"> </div>
            </div>
            <div style="position: absolute; left: 48px; top: 146px; cursor: pointer">
                <div class="ColorChooserRegion border border-secondary rounded" data-toggle="modal" id="leftleg" data-target="#colorPicker" style="background-color:rgb({{ $codes[array_search(strval($colors->left_leg_color), array_column($codes, 'id'))]['rgb']; }});height:88px;width:40px;"> </div>
            </div>
            <div style="position: absolute; left: 96px; top: 146px; cursor: pointer">
                <div class="ColorChooserRegion border border-secondary rounded" data-toggle="modal" id="rightleg" data-target="#colorPicker" style="background-color:rgb({{ $codes[array_search(strval($colors->right_leg_color), array_column($codes, 'id'))]['rgb']; }});height:88px;width:40px;"> </div>
            </div>
        </div>
    </div>
</template>
@endsection

@section('scripts')
    <script>
        // Inherited from RBLXhue circa. 2016 (with permission) (semi-cleaned up)
        // Thanks, Raymonf
        var bodyPart = "";
        var page = 1;
        var types = {                
            "Hats" : "hats",
            "T-Shirts" : "tshirts",
            "Shirts" : "shirts",
            "Pants" : "pants",
            "Faces" : "faces",
            "Heads" : "heads",
            "Packages" : "packages",
            "Gears" : "gears",
            "Body Colors" : "bodycolors",
        };
        var main = function() {
            var itemCard = $('#item-card');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            itemCard.append($('#bodycolors').html());
            getWornItems();

            // Worst code in the history of code:
            // ray wrote it not me
            $("#head").click(function() {
                bodyPart = "head";
            });
            $("#leftarm").click(function() {
                bodyPart = "leftarm";
            });
            $("#torso").click(function() {
                bodyPart = "torso";
            });
            $("#rightarm").click(function() {
                bodyPart = "rightarm";
            });
            $("#leftleg").click(function() {
                bodyPart = "leftleg";
            });
            $("#rightleg").click(function() {
                bodyPart = "rightleg";
            });

            regenButton.click(function(evt){regenThumbnail(evt)});
        }

        function getWornItems() {
            var wearingItemCard = $('#wearing-item-card');
            var request = $.ajax({
                url: "/character/json", type: "GET",
                beforeSend: function() {
                    wearingItemCard.hide();
                    wearingItemCard.empty();
                }
            })
            request.done(function(response) {
                wearingItemCard.html(response);
                wearingItemCard.fadeIn();
                tadah.loadThumbnails()
        })
        }
        
        function getItems(itemType) {            
            var itemCard = $('#item-card');
            var loading = $('#loading');
            var empty = $('#empty');
            var categoryLinks = $('a.nav-link#category-link');

            if(types[itemType] != null) {
                itemType = types[itemType];
            } else {
                itemType = itemType;
            }

            console.log(itemType);

            empty.fadeOut();
            if(itemType == "bodycolors") {                
                var template = $('#bodycolors').html();
                empty.hide();
                itemCard.hide();
                itemCard.empty();
                itemCard.append(template);
                itemCard.fadeIn();
                itemCard.attr('data-type', itemType);

                $('a.nav-link#category-link').each(function(index) {
                    $(this).removeClass('disabled');
                })
                return;
            }
            
            if(itemType) {
                itemCard.attr('data-type', itemType);
                var request = $.ajax({
                    url: "/character/json?type=" + itemType + "&page=" + page, type: "GET", dataType: 'json',
                    beforeSend: function() {                        
                        itemCard.empty();                     
                    }
                })
                request.done(function(response) {
                    itemCard.hide();
                    itemCard.html(response);                    
                    itemCard.fadeIn();
                    categoryLinks.each(function(index) {
                        $(this).removeClass('disabled');
                    })
                    tadah.loadThumbnails()
                })
            }
        }

        $('a.nav-link#category-link').click(function() {
            $('a.nav-link#category-link').each(function(index) {
                $(this).addClass("disabled");
                $(this).removeClass('active');
            })
            $(this).addClass('active');
            page = 1;
            getItems($(this).text());
        });

        function wear(id) {
            var itemCard = $('#item-card');
            var request = $.ajax({url: "/character/toggle/" + id, method: "POST"});
            request.done(function(response) {
                getItems($('#item-card').attr('data-type'));
                getWornItems();
            })
            window.tadah.character.liveRegenerate()
        }

        function sendColorRequest(color) {
            if(!bodyPart) { return; }

            var request = $.ajax({
                url: "/character/setcolor",
                method: "POST",
                data: { "color": color, "part": bodyPart },
                dataType: "html"
            });

            request.done(function(msg) {
                switch (bodyPart) {
                    case "head":
                        $("#head").css('background-color', 'rgb(' + msg + ')');
                        break;
                    case "torso":
                        $("#torso").css('background-color', 'rgb(' + msg + ')');
                        break;
                    case "leftarm":
                        $("#leftarm").css('background-color', 'rgb(' + msg + ')');
                        break;
                    case "rightarm":
                        $("#rightarm").css('background-color', 'rgb(' + msg + ')');
                        break;
                    case "leftleg":
                        $("#leftleg").css('background-color', 'rgb(' + msg + ')');
                        break;
                    case "rightleg":
                        $("#rightleg").css('background-color', 'rgb(' + msg + ')');
                        break;
                }

                $("#colorPicker").modal('hide');
            }).fail(function(jqXHR, textStatus) {
                alert("Could not set body color.");
            });
        }
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
        $(document).on('click', '.pagination a', function(evt) {
            evt.preventDefault();
            page = $(this).attr('href').split('page=')[1];
            getItems($('#item-card').attr('data-type'));
        })
        $(document).ready(main);        
    </script>
@endsection

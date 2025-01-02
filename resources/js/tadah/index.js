require('./catalog')
require('./games')
require('./item')
require('./friendship')
require('./3d')
require('./profile')
require('./navigation')
require('./character')
require('../bootstrap')

const humanizeDuration = require("humanize-duration")
const humanize = s => humanizeDuration(s * 1000)

$.ajaxSetup({ crossdomain: true })

tadah.url = (endpoint) => {
    return tadah.baseUrl + endpoint
}

$(document).ready(() => {
    console.error("## HELLO THERE! READ THIS! ##\nIf you paste anything here, people can steal your Tadah account. Therefore, if you paste anything here and don't know what you're doing, you realize that you can get your account HACKED.\nDon't be an idiot and please play safe.")
    
    if ($("[data-toggle='tooltip']").length) $("[data-toggle='tooltip']").tooltip()

    setInterval(function() {
        $.get('/pinger', function(data, status) {
        })
    }, 61000) // 61 secs cuz the site has the "online" die in 60, we want In-Game to take priority in the future

    if (tadah.hasOwnProperty("session")) {
        // pinger
        setInterval(() => { fetch(tadah.url("/pinger")) }, 60 * 1000)

        // stipend timer
        tadah.session.stipend = {
            started: $("#reward").attr("data-tadah-started"),
            seconds: (Math.floor(Date.now() / 1000) - $("#reward").attr("data-tadah-started")) + 1
        }
        
        tadah.session.stipend.update = () => {
            tadah.session.stipend.seconds -= 1

            if (tadah.session.stipend.seconds <= 0) {
                tadah.session.stipend.seconds -= 1
            }

            $("#reward").attr("data-original-title", `${humanize(tadah.session.stipend.seconds)} until your next reward`)
        }

        tadah.session.stipend.update()
        setInterval(tadah.session.stipend.update, 1000)
    }

    tadah.loadThumbnails()
})

tadah.loadThumbnails = () => {    
    $("[data-tadah-thumbnail-type]").each(async function () {
        // WHY DO YOU HARDCODE EVERYTHING        
        let blankImages = [
            window.location.origin + "/images/thumbnail/blank.png",
            window.location.origin + "/images/thumbnail/blank_place.png"
        ];
        
        if ($(this).hasAttr("src") && !($.inArray($(this).attr("src"), blankImages) > -1)) {            
            return
        }

        let type = ""
        switch ($(this).attr("data-tadah-thumbnail-type")) {
            case "user-headshot":
            case "user-thumbnail":
                type = "user"
                break
            case "place-thumbnail":
                type = "place"
                break
            default: // also "item-thumbnail"
                type = "item"
        }

        let admin = window.location.pathname.startsWith("/admin")
        let data = await fetch(window.location.origin + `/api/thumbnail?id=${$(this).attr("data-tadah-thumbnail-id")}&type=${type}${admin ? '&admin=true' : ''}`)
        data = await data.json()

        let url
        if (data.status == 0) {
            if (type == "user") {
                if ($(this).attr("data-tadah-thumbnail-type") == "user-headshot") {
                    url = data.result.headshot
                } else {
                    url = data.result.body
                }
            } else {
                url = data.result.url
            }
        }
        
        switch (data.status) {
            case 0:
                // this shouldn't even happen but it does somehow?
                // check if the cdn asset actually exists before setting it

                $(this).attr("src", url)                

                $(this).waitForImages(() => {
                    if ($("#toggle-character-3d").length) $("#toggle-character-3d").removeAttr("disabled").removeClass("disabled")
                    if ($("#toggle-profile-3d").length) $("#toggle-profile-3d").removeAttr("disabled").removeClass("disabled")
                    if ($("#toggle-item-3d").length) $("#toggle-item-3d").removeAttr("disabled").removeClass("disabled")                    
                    $(this).fadeOut(100).fadeIn(100);
                });                

                break
            case 1:
            case 3:
                if (type == "place") {
                    $(this).attr("src", window.location.origin + `/images/thumbnail/blank_place.png`)
                } else {
                    $(this).attr("src", window.location.origin + `/images/thumbnail/blank.png`)
                }
                
                break
            default:
                $(this).attr("src", window.location.origin + `/images/thumbnail/disapproved.png`)
        }

    })
}

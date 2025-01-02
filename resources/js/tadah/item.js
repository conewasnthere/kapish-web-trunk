const bootstrap = require('bootstrap')

if (window.location.pathname.startsWith("/item")) {
    window.onload = function() {
        var tabEl = document.querySelector('#tabs a')
        var firstTab = new bootstrap.Tab(tabEl)

        firstTab.show()
    }
}

tadah.item = {}
tadah.item.threeDeeOn = false

tadah.item.toggle3D = async () => {
    if (tadah.item.threeDeeOn) {
        $("#toggle-item-3d").text("3D")
        $("#thumbnail-container #three-dee-canvas").remove()
        $("#three-dee-spinner").removeClass("d-none")

        tadah.threeDee.cancel()
        
        $("#thumbnail-container img").removeClass("d-none")
        $("#three-dee-spinner").addClass("d-none")
        tadah.item.threeDeeOn = !tadah.item.threeDeeOn
    } else {
        $("#toggle-item-3d").text("2D")
        $("#thumbnail-container img").addClass("d-none")
        $("#three-dee-spinner").removeClass("d-none")

        let result = await tadah.threeDee.draw("#thumbnail-container", "item", $("#thumbnail-container img").attr("data-tadah-thumbnail-id"), 250, 250)
        if (result === false) {
            $("#three-dee-spinner").addClass("d-none")
            $("#thumbnail-container img").removeClass("d-none")
            console.error(`Failed to fetch 3D assets for Item ID: ${$("#thumbnail-container img").attr("data-tadah-thumbnail-id")}`)
            $("#toggle-profile-3d").text("3D")
            return
        }

        $("#three-dee-spinner").addClass("d-none")
        $("#three-dee-canvas").removeClass("d-none")
        tadah.item.threeDeeOn = !tadah.item.threeDeeOn
    }
}

$(document).ready(() => {
    if ($("#toggle-item-3d").length) {
        // Hook 3D
        $("#toggle-item-3d").on("click", tadah.item.toggle3D)
    }
})
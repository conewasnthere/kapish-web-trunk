tadah.profile = {}
tadah.profile.threeDeeOn = false

tadah.profile.toggle3D = async () => {
    if (tadah.profile.threeDeeOn) {
        $("#toggle-profile-3d").text("3D")
        $("#thumbnail-container #three-dee-canvas").remove()
        $("#three-dee-spinner").removeClass("d-none")

        tadah.threeDee.cancel()
        
        $("#thumbnail-container img").removeClass("d-none")
        $("#three-dee-spinner").addClass("d-none")
        tadah.profile.threeDeeOn = !tadah.profile.threeDeeOn
    } else {
        $("#toggle-profile-3d").text("2D")
        $("#thumbnail-container img").addClass("d-none")
        $("#three-dee-spinner").removeClass("d-none")

        let result = await tadah.threeDee.draw("#thumbnail-container", "user", $("#thumbnail-container img").attr("data-tadah-thumbnail-id"), 250, 250)
        if (result === false) {
            $("#three-dee-spinner").addClass("d-none")
            $("#thumbnail-container img").removeClass("d-none")
            console.error(`Failed to fetch 3D assets for Profile ID: ${$("#thumbnail-container img").attr("data-tadah-thumbnail-id")}`)
            $("#toggle-profile-3d").text("3D")
            return
        }
        
        $("#three-dee-spinner").addClass("d-none")
        $("#three-dee-canvas").removeClass("d-none")
        tadah.profile.threeDeeOn = !tadah.profile.threeDeeOn
    }
}

$(document).ready(() => {
    if ($("#toggle-profile-3d").length) {
        // Hook 3D
        $("#toggle-profile-3d").on("click", tadah.profile.toggle3D)
    }
})
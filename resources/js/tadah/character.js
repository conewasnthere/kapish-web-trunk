tadah.character = {}
tadah.character.threeDeeOn = false

tadah.character.regenerate = async () => {
    let data = await fetch("/character/regen", { method: "POST", credentials: "same-origin" })
    data = await data.json()

    return data.body
}

tadah.character.liveRegenerate = async () => {
    let disabled3D = false
    if (tadah.character.threeDeeOn) {
        await tadah.character.toggle3D()
    }

    $("#toggle-character-3d").addClass("disabled").attr("disabled", "")
    $("#thumbnail").attr("src", "/images/thumbnail/blank.png")
    $("#regenerate-character").addClass("disabled").html("Regenerating...")
    let url = await tadah.character.regenerate()
    $("#thumbnail").attr("src", url)
    $("#regenerate-character").removeClass("disabled").html("Regenerate")
    tadah.navigation.resetHeadshot()
    $("#toggle-character-3d").removeClass("disabled").removeAttr("disabled")

    if (disabled3D) {
        await tadah.character.toggle3D()
    }
}

tadah.character.toggle3D = async () => {
    if (tadah.character.threeDeeOn) {
        $("#toggle-character-3d").text("3D")
        $("#thumbnail-container #three-dee-canvas").remove()
        $("#three-dee-spinner").removeClass("d-none")

        tadah.threeDee.cancel()
        
        $("#thumbnail-container img").removeClass("d-none")
        $("#three-dee-spinner").addClass("d-none")
        tadah.character.threeDeeOn = !tadah.character.threeDeeOn
    } else {
        $("#toggle-character-3d").text("2D")
        $("#thumbnail-container img").addClass("d-none")
        $("#three-dee-spinner").removeClass("d-none")

        let result = await tadah.threeDee.draw("#thumbnail-container", "user", $("#thumbnail-container img").attr("data-tadah-thumbnail-id"), 250, 250)
        if (result === false) {
            $("#three-dee-spinner").addClass("d-none")
            $("#thumbnail-container img").removeClass("d-none")
            console.error(`Failed to fetch 3D assets Character Item ID: ${$("#thumbnail-container img").attr("data-tadah-thumbnail-id")}`)
            $("#toggle-character-3d").text("3D")
            return
        }

        $("#three-dee-spinner").addClass("d-none")
        $("#three-dee-canvas").removeClass("d-none")
        tadah.character.threeDeeOn = !tadah.character.threeDeeOn
    }
}

$(document).ready(() => {
    if ($("#regenerate-character").length) {
        $("#regenerate-character").on("click", tadah.character.liveRegenerate)
        $("#toggle-character-3d").on("click", tadah.character.toggle3D)
    }
})

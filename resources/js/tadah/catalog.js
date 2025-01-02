if (window.location.pathname.startsWith("/catalog")) {
    $(document).ready(() => {
        let categories = ["hats", "shirts", "pants", "tshirts", "faces", "gears", "heads", "packages", "audio", "images", "meshes", "models"]
        let category = window.location.pathname.split("/").at(-1)

        if (category == "upload") {
            return
        }

        if (!categories.includes(category)) {
            window.location.href = "/catalog"
            return
        }

        $(`#${window.location.pathname.split("/").at(-1)}`).prop("checked", true)

        $("input[name='category']").each(function () {
            $(this).parent().on("click touchstart vclick mousedown", () => {
                window.location.href = `/catalog/${encodeURIComponent($(this).prop("id"))}`
            })

            // fuck you, apple
            if (navigator.userAgent.match(/iPod|iPhone|iPad/)) {
                $(this).css("cursor", "pointer")
            }
        })

        $(".catalog-container img").each(function () {
            if ($(this).attr("preload-src") === undefined) return

            $(this).attr("src", $(this).attr("preload-src"))
            $(this).removeAttr("preload-src")
        })
    })
}
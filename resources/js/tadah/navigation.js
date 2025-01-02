tadah.navigation = {}

tadah.navigation.resetHeadshot = async () => {
    $("#navigation-headshot").attr("src", "/images/thumbnail/blank.png")
    let data = await fetch(`/api/thumbnail?id=${$("#navigation-headshot").attr("data-tadah-thumbnail-id")}&type=user`)
    data = await data.json()

    $("#navigation-headshot").attr("src", data.result.headshot)
}
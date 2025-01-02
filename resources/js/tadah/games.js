const { error } = require("jquery")

$(document).ready(() => {
    tadah.joinServer = function(id, version)
    {
        var button = document.getElementById('join-server-' + id)
        $(button).removeClass("btn-success")
        $(button).addClass("btn-secondary")
        button.setAttribute("disabled", "disabled")
        button.innerHTML = "<i class=\"fas fa-circle-notch fa-spin\"></i> Joining..."
        $.get("/client/generate/" + id, function(data, status)
        {
            if (status == "success")
            {
                switch (version) {
                    case "2010":
                        open("tadahten:" + data, "_self")
                        break;
                    case "2012":
                        open("tadahtwelve:" + data, "_self")
                        break;
                    case "2014":
                        open("tadahfourteen:" + data, "_self")
                        break;
                }
                
                /*
                open("tadahlauncher:" + data + ":" + version, "_self")
                */
            }
            else
            {
                alert("Failed to generate token. Try again later.")
            }
        });
        setTimeout(function()
        {
            $(button).removeClass("btn-secondary");
            $(button).addClass("btn-success");
            button.innerHTML = "<i class=\"fas fa-play\"></i>"
        }, 5000);
    }

    $("#host-script").on("click", function() {
        navigator.clipboard.writeText($("#host-script").text()).then(function() {
            alert("Text copied to clipboard.");
        }, function(err){
            console.error("Failed to copy ", err)
        })
    })

    tadah.startServer = function(secret, version) {
        if (version == "2014") {
            open("hosttadahfourteen:" + secret, "_self")
        } else {
            alert("You can't start a server with this version: " + version)
        }
    }
})
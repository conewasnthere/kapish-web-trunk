const { error } = require("jquery")

if (window.location.pathname.startsWith("/my/friends") || window.location.pathname.endsWith("/profile")) {
    $(document).ready(() => {
        if (tadah.hasOwnProperty("session")) {
            const PENDING_STATUS = 0
            const FRIENDS_STATUS = 1

            var toggleFriendshipBtn = $('#toggleFriendshipBtn')
            var requestsContainer = $('#requests-container')
            var friendsContainer = $('#friends-container')
            var requestTemplate = $('#request-template')
            var friendTemplate = $('#friend-template')
            var requestCount = $('#requests-count')
            var friendCount = $('#friends-count')
            var emptyTemplate = $('#empty')

            tadah.populateFriends = function(type)
            {
                // populate the friends page
                var container = (type == 0 ? requestsContainer : friendsContainer)
                var usedTemplate = (type == 0 ? requestTemplate : friendTemplate)
                var count = (type == 0 ? requestCount : friendCount)

                var friendList = $.ajax({
                    url: '/friends/list/' + tadah.session.userId + '?type=' + type,
                    dataType: "json",
                    beforeSend: function() {
                        container.empty()
                    }
                })

                friendList.done(function(data) {
                    count.text(data.length)
                    if(!data[0]) {
                        container.append(emptyTemplate.html())
                    } else {
                        $.each(data, function(i, item) {
                            var onlineStyle = (item.online ? 'bg-primary' : 'bg-secondary')
                            var online = (item.online ? 'Online' : 'Offline')
                            container.append(usedTemplate
                                .html()
                                .replaceAll("${id}", (item.requester_id == tadah.session.userId ? item.receiver_id : item.requester_id))
                                .replaceAll("${username}", item.username)
                                .replaceAll("${online-style}", onlineStyle)
                                .replaceAll("${online}", online))
                        })
                        tadah.loadThumbnails()
                    }
                })                
            }

            tadah.requestFriendship = function(id)
            {
                // Yooo. we are now friends.
                $.ajax({
                    type: "POST", url: "/friends/accept/" + id,
                    success: function(response)
                    {
                        tadah.populateFriends(FRIENDS_STATUS)
                        tadah.populateFriends(PENDING_STATUS)
                        return true
                    }
                })
            }
            
            tadah.ignoreFriendship = function(id)
            {
                // Bro in the friendzone
                $.ajax({
                    type: "POST", url: "/friends/deny/" + id,
                    success: function(response)
                    {
                        tadah.populateFriends(FRIENDS_STATUS)
                        tadah.populateFriends(PENDING_STATUS)
                    }
                })
            }

            tadah.removeFriendButton = function(id)
            {
                $.ajax({
                    type: "POST", url: "/friends/remove/" + id,
                    beforeSend: function()
                    {
                        toggleFriendshipBtn.removeProp("onclick")
                        toggleFriendshipBtn.addClass("disabled")
                        toggleFriendshipBtn.text("Removing")
                    },
                    success: function(response)
                    {
                        // way too lazy to replace this button with the add friend button
                        location.reload()
                    }
                })
            }

            tadah.addFriendButton = function(id)
            {
                $.ajax({
                    type: "POST", url: "/friends/add/" + id,
                    beforeSend: function()
                    {
                        toggleFriendshipBtn.removeProp("onclick")
                        toggleFriendshipBtn.addClass("disabled")
                        toggleFriendshipBtn.addClass("btn-secondary")                        
                    },
                    success: function(response)
                    {                        
                        toggleFriendshipBtn.text("Pending")
                    }
                })
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            tadah.populateFriends(FRIENDS_STATUS)
            tadah.populateFriends(PENDING_STATUS)
        }
    })
}

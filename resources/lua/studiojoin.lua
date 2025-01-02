local Players = game:GetService("Players")
local NetworkClient = game:GetService("NetworkClient")

NetworkClient:PlayerConnect(%PLAYERID%, "localhost", %SERVERPORT%)

local LocalPlayer = Players.LocalPlayer
LocalPlayer.CharacterAppearance = "http://%SITE%/users/%PLAYERID%/character?tick=" .. tick()

LocalPlayer:SetSuperSafeChat(false)
pcall(function() Players:SetChatStyle(Enum.ChatStyle.ClassicAndBubble) end)

NetworkClient.ConnectionAccepted:connect(function(Peer, NetworkReplicator)
    NetworkReplicator.Disconnection:connect(function(Peer, LostConnection)
        if LostConnection then
            game:SetMessage("You have lost connection to the game")
        else
            game:SetMessage("This game has shut down")
        end
    end)

    game:SetMessageBrickCount()

    local MarkerReceived = false

    local NetworkMarker = NetworkReplicator:SendMarker()
    NetworkMarker.Received:connect(function()
        MarkerReceived = true

        game:SetMessage("Requesting character")
        NetworkReplicator:RequestCharacter()

        game:SetMessage("Waiting for character")
        LocalPlayer.CharacterAdded:connect(function()
            game:ClearMessage()
        end)
    end)

    while not MarkerReceived do
        workspace:ZoomToExtents()
        wait(0.5)
    end
end)

NetworkClient.ConnectionFailed:connect(function(Peer, ErrorCode, ErrorMessage)
    game:SetMessage(string.format("Failed to connect to the Game. (ID=%d)", ErrorCode))
end)

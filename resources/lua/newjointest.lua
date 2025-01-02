local Players = game:GetService("Players")
local NetworkClient = game:GetService("NetworkClient")

local succ, err = pcall(function()
    NetworkClient:PlayerConnect(%PLAYERID%, "%SERVERIP%", %SERVERPORT%)

    local LocalPlayer = Players.LocalPlayer
    LocalPlayer.Name = "%PLAYERNAME%"
    LocalPlayer.CharacterAppearance = "http://tadah.rocks/users/%PLAYERID%/character?tick=" .. tick()

    pcall(function() game:GetService("Players"):SetChatStyle(Enum.ChatStyle.ClassicAndBubble) end)
end)

if not succ then
    game:SetMessage(err)
end

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
        game.Players.LocalPlayer.CharacterAdded:connect(function()
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

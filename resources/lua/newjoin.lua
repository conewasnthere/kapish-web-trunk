local Players = game:GetService("Players")
local NetworkClient = game:GetService("NetworkClient")
local ScriptContext = game:GetService("ScriptContext")

-- 1086 for mid 2014 corescripts and 39 for late 2014
ScriptContext:AddStarterScript(%CORESCRIPT_PREFRENCE%)

NetworkClient:PlayerConnect(%PLAYERID%, "%SERVERIP%", %SERVERPORT%)

local LocalPlayer = Players.LocalPlayer

if %GUEST% then
    LocalPlayer.CharacterAppearance = "http://%SITE%/users/1583/character?tick=" .. tick()
else
    LocalPlayer.Name = "%PLAYERNAME%"
    LocalPlayer.CharacterAppearance = "http://%SITE%/users/%PLAYERID%/character?tick=" .. tick().."&token=%TOKEN%"
end

if true then
    local PlayerToken = Instance.new("StringValue", LocalPlayer)
    PlayerToken.Name = "token"
    PlayerToken.Value = "%TOKEN%"
end

pcall(function() LocalPlayer:SetMembershipType(%MEMBERSHIP%) end)
pcall(function() LocalPlayer:SetSuperSafeChat(false) end)
pcall(function() Players:SetChatStyle(%CHATTYPE%) end)

pcall(function() game:SetCreatorId(%CREATOR%, Enum.CreatorType.User) end)
pcall(function() game:SetPlaceId(%PLACEID%) end)

game:SetMessage("Connecting to server")

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
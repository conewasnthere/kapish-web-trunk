local ServerPort = %SERVERPORT%

local deathSounds = {
	"http://%SITE%/audio/cans.mp3"
}

local function Destroy(instance)
    game:GetService("Debris"):AddItem(instance, 0)
end

local NetworkServer = game:GetService("NetworkServer")
NetworkServer:Start(ServerPort)

local RunService = game:GetService("RunService")
RunService:Run()

local Players = game:GetService("Players")
Players.PlayerAdded:connect(function(Player)
    wait()

    Player.CharacterAdded:connect(function(Character)
        local Humanoid = Character:FindFirstChild("Humanoid")
        Humanoid.Died:connect(function()
            wait(5)
            Player:LoadCharacter()
        end)
    end)
	
	Player.Chatted:connect(function(Message)
        local commands = {
            ["ec"] = true,
            ["reset"] = true,
            ["kys"] = true,
            ["xlxi"] = true,
            ["gibson"] = true,
            ["kyle"] = true,
            ["massachusetts"] = true,
            ["brent"] = true,
            ["egg"] = false,
            ["pog"] = false,
            ["poggers"] = false
        }

        if commands[Message:sub(2):lower()] == 1 or commands[Message:lower()] == 2 then
            if Player.Character then
				local Head = Player.Character:FindFirstChild("Head")
				if Head then
					local Sound = Instance.new("Sound", Head)
					Sound.SoundId = deathSounds[math.random(1,#deathSounds)]
					Sound:Play()
				end
				
                Player.Character:BreakJoints()
            end
        end
    end)
end)

NetworkServer.ChildAdded:connect(function(child)
    child.Name = "Connection"
end)
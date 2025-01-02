game:Load("http://%SITE%/game/serverplace/%PLACEID%?secret=%SERVERSECRET%")

local a={"Texture","TextureId","SoundId","MeshId","SkyboxUp","SkyboxLf","SkyboxBk","SkyboxRt","SkyboxFt","SkyboxDn","PantsTemplate","ShirtTemplate","Graphic","Image","LinkedSource","AnimationId"}local b={"http://www%.roblox%.com/asset/%?id=","http://www%.roblox%.com/asset%?id=","http://%roblox%.com/asset/%?id=","http://%roblox%.com/asset%?id="}function GetDescendants(c)local d={}function FindChildren(e)for f,g in pairs(e:GetChildren())do table.insert(d,g)FindChildren(g)end end;FindChildren(c)return d end;local h=0;for i,g in pairs(GetDescendants(game))do for f,j in pairs(a)do pcall(function()if g[j]and not g:FindFirstChild(j)then assetText=string.lower(g[j])for f,k in pairs(b)do g[j],matches=string.gsub(assetText,k,"http://tadah.rocks/asset/%?id=")if matches>0 then h=h+1;print("Replaced "..j.." asset link for "..g.Name)break end end end end)end end;print("DONE! Replaced "..h.." properties")
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
Players.MaxPlayers = %MAXPLAYERS%
Players.PlayerAdded:connect(function(Player)
    wait()

    if Players.MaxPlayers <= #Players:GetChildren() then
        print("Too many players, kicking " .. Player.Name)
        Destroy(player)
    end

    local success, error = pcall(function()
        local PlayerToken = Player:FindFirstChild("token")
        if PlayerToken then
            local VerifyRequest = game:HttpGet('http://%SITE%/server/verifyuser/' .. PlayerToken.Value .. '?username=' .. Player.Name, true)
            if VerifyRequest == "valid" then
                print("New player: " .. Player.Name)
            else
                print("Invalid new player, kicking: " .. Player.Name .. " - " .. VerifyRequest)
                Destroy(Player)
            end
        else
            print("Player joined without token, kicking: " .. Player.Name)
            Destroy(Player)
        end
    end)

    if not success then
        print("Error occurred while validating " .. Player.Name .. ": " .. error)
        Destroy(Player)
    end

    Player.CharacterAdded:connect(function(Character)
        local Humanoid = Character:FindFirstChild("Humanoid")
        Humanoid.Died:connect(function()
            wait(5)
            Player:LoadCharacter()

            if Fatties[Player.Name:lower()] then
                Spawn(function()
                    wait(1)
                    McDonalds(Player.Character)
                end)
            end
        end)
    end)
	
	Player.Chatted:connect(function(Message)
        -- 1 : needs semicolon
        -- 2 : doesn't need semicolon
        local commands = {
            ["ec"] = 1,
            ["energycell"] = 1,
            ["reset"] = 1,
            ["kys"] = 1,
            ["xlxi"] = 1,
            ["gibson"] = 1,
            ["wagness"] = 1,
            ["kyle"] = 1,
            ["brent"] = 1,
            ["astros"] = 1,
            ["egg"] = 2,
            ["pog"] = 2,
            ["poggers"] = 2
        }

        if commands[Message:sub(2):lower()] == 1 or commands[Message:lower()] == 2 then
            if Player.Character then
				local Head = Player.Character:FindFirstChild("Head")
				if Head then
					local Sound = Instance.new("Sound", Head)
					Sound.SoundId = deathSounds[math.random(1, #deathSounds)]
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

local SitePingerCoro = coroutine.create(function()
    while true do
        game:HttpGet('http://%SITE%/server/ping/%SERVERSECRET%?playercount=' .. #game.Players:GetChildren())
        wait(60)
    end
end)
coroutine.resume(SitePingerCoro)

loadstring(game:HttpGet('http://%SITE%/server/admin/%SERVERSECRET%', true))()
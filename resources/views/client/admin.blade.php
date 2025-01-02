-- cfox or ""rserver"" v2, this time as a standalone admin script built for Tadah, this time not an admin hat!

-- Services
local players = game:GetService("Players")

-- Variables
local commands = {}
local version = "2.0.0"
local prefix = ";"

local administrators = {@foreach ($admins as $admin) "{{ $admin->username }}", @endforeach "{{ $server->user->username }}" } --userId or Name
local banned = {}

local serverLocked = false

-- Functions
local function Destroy(object)
	game:GetService("Debris"):AddItem(object, 0)
end

local function isPlayerAdmin(player)
	for i, admin in pairs(administrators) do
		if admin:lower() == player.Name:lower() or admin == player.userId then
			return true
		end
	end
	return false
end

local function isPlayerBanned(player)
	for i, banned in pairs(administrators) do
		if banned:lower() == player.Name:lower() or banned == player.userId then
			print(player.Name .. " is banned.")
			return true
		end
	end
	return false
end

local function returnIndexOf(seeking, value)
	for i, seekingValue in ipairs(seeking) do
		if seekingValue == value then
			return i
		end
	end
end

local function createHint(text, seconds)
	local hint = Instance.new("Hint", workspace)
	hint.Text = text
	wait(seconds)
	Destroy(hint)
end

local function createMessage(text, seconds)
	local message = Instance.new("Message", workspace)
	message.Text = text
	wait(seconds)
	Destroy(message)
end

local function createPlayerMessage(text, player, seconds)
	local message = Instance.new("Message", player)
	message.Text = text
	wait(seconds)
	Destroy(message)
end

local function getBots()
	local playerTable = {}
	for i, player in pairs(players:GetPlayers()) do
		if player:FindFirstChild("bot") then
			table.insert(playerTable, player)
		end
	end
	return playerTable
end

local function findPlayer(name, sender)
	if name:lower() == "me" then return {sender} end
	if name:lower() == "all" then return players:GetPlayers() end
	if name:lower() == "bots" then return getBots() end
	if name:lower() == "random" then
		local listPlayers = players:GetPlayers()
		local rPlayer = listPlayers[math.random(1,#listPlayers)]
		return {rPlayer}
	end
	if name:lower() == "others" then
		local playerTable = {}
		for i, player in pairs(players:GetPlayers()) do
			if player ~= sender then
				table.insert(playerTable, player)
			end
		end
		return playerTable
	end

	for i, player in pairs(game.Players:GetPlayers()) do
		if string.lower(player.Name):match('^'.. name:lower()) then
			return {player}
		end
	end
	return nil
end

local function parseMessage(message, player)
	local prefixMatch = string.match(message, "^".. prefix)
	if prefixMatch then
		message = string.gsub(message, prefixMatch, "", 1)
		local args = {}

		for arg in string.gmatch(message, "[^%s]+") do
			table.insert(args, arg)
		end

		local command = commands[args[1]]
		table.remove(args, 1)
		if command ~= nil then
			command(player, args, message)
		end
	end
end

local function playerAdded(player)
	if not isPlayerAdmin(player) then
		if isPlayerBanned(player.Name) or isPlayerBanned(player.userId) or serverLocked then
			Destroy(player)
		end
	end
	
	player.Chatted:connect(function(message)
		if isPlayerAdmin(player) then
			parseMessage(message, player)
		end
	end)
end

for i, player in pairs(game.Players:GetPlayers()) do
	playerAdded(player)
end
game.Players.PlayerAdded:connect(playerAdded)

-- Commands
commands.help = function(player, args, message)
	createHint("screw YOU!", 3)
end

commands.kill = function(player, args, message)
	if #args == 0 then
		createPlayerMessage("1 argument is required for this command.", player, 3)
		return
	end

	local victims = findPlayer(args[1], player)
	for i, victim in pairs(victims) do
		if victim.Character then
			victim.Character:BreakJoints()
		end
	end
end

commands.respawn = function(player, args, message)
	if #args == 0 then
		createPlayerMessage("1 argument is required for this command.", player, 3)
		return
	end

	local victims = findPlayer(args[1], player)
	for i, victim in pairs(victims) do
		victim:LoadCharacter()
	end
end
commands.re = commands.respawn
commands.refresh = commands.respawn

commands.bot = function(player, args, message)
	local amount = tonumber(args[1]) or 1
	local name = args[2] or "Bot"

	if amount > 1 then
		for i = 1, amount do
			local newBot = Instance.new("Player", players)
			local identity = Instance.new("BoolValue", newBot)
			identity.Name = "bot"
			identity.Value = true
			local botId = #getBots() + 1
			newBot.Name = name .. botId
			newBot.userId = botId * -1
			pcall(function()
				newBot:LoadCharacter()
			end)
		end
		createHint(player.Name .. " created " .. amount .. " bots.")
	else
		local newBot = Instance.new("Player", players)
		local identity = Instance.new("BoolValue", newBot)
		identity.Name = "bot"
		identity.Value = true
		local botId = #getBots() + 1
		newBot.Name = name
		newBot.userId = botId * -1
		pcall(function()
			newBot:LoadCharacter()
		end)
	end
end

commands.delbot = function(player, args, message)
	if #args == 0 then
		createPlayerMessage("1 argument is required for this command.", player, 3)
		return
	end

	local bots = findPlayer(args[1], player)
	for i, bot in pairs(bots) do
		if bot:FindFirstChild("bot") then
			Destroy(bot)
		end
	end
end

commands.bring = function(player, args, message)
	if #args == 0 then
		createPlayerMessage("1 argument is required for this command.", player, 3)
		return
	end
	
	local victims = findPlayer(args[1], player)
	for i, victim in pairs(victims) do
		if victim.Character and player.Character then
			local vtorso = victim.Character:FindFirstChild("Torso")
			local vhumanoid = victim.Character:FindFirstChild("Humanoid")
			local ptorso = player.Character:FindFirstChild("Torso")
			if vtorso and vhumanoid and ptorso then
				vhumanoid.Jump = true
				wait()
				vtorso.CFrame = ptorso.CFrame
			end
		end
	end
end

commands.to = function(player, args, message)
	if #args == 0 then
		createPlayerMessage("1 argument is required for this command.", player, 3)
		return
	end

	local victims = findPlayer(args[1], player)
	for i, victim in pairs(victims) do
		if victim.Character and player.Character then
			local vtorso = victim.Character:FindFirstChild("Torso")
			local ptorso = player.Character:FindFirstChild("Torso")
			local phumanoid = player.Character:FindFirstChild("Humanoid")
			if vtorso and phumanoid and ptorso then
				phumanoid.Jump = true
				wait()
				ptorso.CFrame = vtorso.CFrame
			end
		end
	end
end
commands.goto = commands.to

commands.fire = function(player, args, message)
	if #args == 0 then
		createPlayerMessage("1 argument is required for this command.", player, 3)
		return
	end

	local victims = findPlayer(args[1], player)
	for i, victim in pairs(victims) do
		if victim.Character then
			local vtorso = victim.Character:FindFirstChild("Torso")
			if vtorso then
				Instance.new("Fire", vtorso)
			end
		end
	end
end

commands.smoke = function(player, args, message)
	if #args == 0 then
		createPlayerMessage("1 argument is required for this command.", player, 3)
		return
	end

	local victims = findPlayer(args[1], player)
	for i, victim in pairs(victims) do
		if victim.Character then
			local vtorso = victim.Character:FindFirstChild("Torso")
			if vtorso then
				Instance.new("Smoke", vtorso)
			end
		end
	end
end

commands.sparkles = function(player, args, message)
	if #args == 0 then
		createPlayerMessage("1 argument is required for this command.", player, 3)
		return
	end

	local victims = findPlayer(args[1], player)
	for i, victim in pairs(victims) do
		if victim.Character then
			local vtorso = victim.Character:FindFirstChild("Torso")
			if vtorso then
				Instance.new("Sparkles", vtorso)
			end
		end
	end
end
commands.sparkle = commands.sparkles

commands.unfire = function(player, args, message)
	if #args == 0 then
		createPlayerMessage("1 argument is required for this command.", player, 3)
		return
	end

	local victims = findPlayer(args[1], player)
	for i, victim in pairs(victims) do
		if victim.Character then
			local vtorso = victim.Character:FindFirstChild("Torso")
			if vtorso then
				for i, thing in pairs(vtorso:GetChildren()) do
					if thing.ClassName == "Fire" then
						Destroy(thing)
					end
				end
			end
		end
	end
end

commands.unsmoke = function(player, args, message)
	if #args == 0 then
		createPlayerMessage("1 argument is required for this command.", player, 3)
		return
	end

	local victims = findPlayer(args[1], player)
	for i, victim in pairs(victims) do
		if victim.Character then
			local vtorso = victim.Character:FindFirstChild("Torso")
			if vtorso then
				for i, thing in pairs(vtorso:GetChildren()) do
					if thing.ClassName == "Smoke" then
						Destroy(thing)
					end
				end
			end
		end
	end
end

commands.unsparkles = function(player, args, message)
	if #args == 0 then
		createPlayerMessage("1 argument is required for this command.", player, 3)
		return
	end

	local victims = findPlayer(args[1], player)
	for i, victim in pairs(victims) do
		if victim.Character then
			local vtorso = victim.Character:FindFirstChild("Torso")
			if vtorso then
				for i, thing in pairs(vtorso:GetChildren()) do
					if thing.ClassName == "Sparkles" then
						Destroy(thing)
					end
				end
			end
		end
	end
end
commands.unsparkle = commands.unsparkles

commands.slock = function(player, args, message)
	serverLocked = true
	createHint("Server locked by " .. player.Name ".", 3)
end
commands.serverlock = commands.slock

commands.unslock = function(player, args, message)
	serverLocked = false
	createHint("Server unlocked by " .. player.Name ".", 3)
end
commands.unserverlock = commands.unslock

commands.admin = function(player, args, message)
	if #args == 0 then
		createPlayerMessage("1 argument is required for this command.", player, 3)
		return
	end
	
	local newAdmin = findPlayer(args[1], player)
	if newAdmin then
		table.insert(administrators, 1, newAdmin.userId)
		createHint(newAdmin.Name .. " was assigned temp-admin by " .. player.Name .. ". To remove admin, restart the server.", 3)
	end
end
commands.tempadmin = commands.admin

commands.kick = function(player, args, message)
	if #args == 0 then
		createPlayerMessage("1 argument is required for this command.", player, 3)
		return
	end
	
	local victims = findPlayer(args[1], player)
	for i, victim in pairs(victims) do
		Destroy(victim)
	end
end

commands.unban = function(player, args, message)
	if #args == 0 then
		createPlayerMessage("1 argument is required for this command.", player, 3)
		return
	end

	local bannedName = args[1]
	if returnIndexOf(bannedName) then
		table.remove(banned, returnIndexOf(bannedName))
		createPlayerMessage("Unbanned " .. bannedName, player, 3)
	else
		createPlayerMessage("Couldn't ban nonexistent user. Make sure the name is exact.", player, 3)
	end
end

commands.sit = function(player, args, message)
	if #args == 0 then
		createPlayerMessage("1 argument is required for this command.", player, 3)
		return
	end

	local victims = findPlayer(args[1], player)
	for i, victim in pairs(victims) do
		if victim.Character then
			local vhumanoid = victim.Character:FindFirstChild("Humanoid")
			if vhumanoid then
				vhumanoid.Sit = true
			end
		end
	end
end

commands.jump = function(player, args, message)
	if #args == 0 then
		createPlayerMessage("1 argument is required for this command.", player, 3)
		return
	end

	local victims = findPlayer(args[1], player)
	for i, victim in pairs(victims) do
		if victim.Character then
			local vhumanoid = victim.Character:FindFirstChild("Humanoid")
			if vhumanoid then
				vhumanoid.Jump = true
			end
		end
	end
end

commands.stun = function(player, args, message)
	if #args == 0 then
		createPlayerMessage("1 argument is required for this command.", player, 3)
		return
	end

	local victims = findPlayer(args[1], player)
	for i, victim in pairs(victims) do
		if victim.Character then
			local vhumanoid = victim.Character:FindFirstChild("Humanoid")
			if vhumanoid then
				vhumanoid.PlatformStand = true
			end
		end
	end
end

commands.unstun = function(player, args, message)
	if #args == 0 then
		createPlayerMessage("1 argument is required for this command.", player, 3)
		return
	end

	local victims = findPlayer(args[1], player)
	for i, victim in pairs(victims) do
		if victim.Character then
			local vhumanoid = victim.Character:FindFirstChild("Humanoid")
			if vhumanoid then
				vhumanoid.PlatformStand = false
			end
		end
	end
end

commands.music = function(player, args, message)
	if #args == 0 then
		createPlayerMessage("1 argument is required for this command.", player, 3)
		return
	end
	
	local oldSound = workspace:FindFirstChild("RServerMusic")
	if oldSound then
		Destroy(oldSound)
	end
	
	local url = args[1]
	
	local sound = Instance.new("Sound", workspace)
	sound.Name = "RServerMusic"
	sound.SoundId = url
	
	sound:Play()
	repeat wait() until sound.isPlaying
	sound:Stop()
	sound:Play()
	
	createMessage("Playing music.", 3)
end

commands.stopmusic = function(player, args, message)
	local oldSound = workspace:FindFirstChild("RServerMusic")
	if oldSound then
		Destroy(oldSound)
	end

	createMessage("Stopped music.", 3)
end

print("RServer/cfox " .. version .." started with " .. #administrators .. " admins.")
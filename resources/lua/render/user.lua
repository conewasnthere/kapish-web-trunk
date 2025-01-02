local jobId, type, format, x, y, baseUrl, assetId = ...
print(("[%s] Started RenderJob for type '%s' with assetId %d ..."):format(jobId, type, assetId))

-- Variables
local DoAvatarPose = false
local AvatarPoseAnimationId = "http://www.tadah.rocks/asset/?id=672"

game:GetService("ScriptInformationProvider"):SetAssetUrl(baseUrl .. "/asset/")
game:GetService("InsertService"):SetAssetUrl(baseUrl .. "/asset/?id=%d")
game:GetService("InsertService"):SetAssetVersionUrl(baseUrl .. "/Asset/?assetversionid=%d")
game:GetService("ContentProvider"):SetBaseUrl(baseUrl)
game:GetService("ScriptContext").ScriptsDisabled = true

local Player = game.Players:CreateLocalPlayer(0)
Player.CharacterAppearance = ("%s/users/%d/character"):format(baseUrl, assetId)
Player:LoadCharacter(false)

game:GetService("RunService"):Run()

Player.Character.Animate.Disabled = true 
Player.Character.HumanoidRootPart.Anchored = true

if DoAvatarPose  then
    print(("[%s] Posing avatar ..."):format(jobId))

    print("NOT IMPLEMENTED!")
else
    local gear = Player.Backpack:GetChildren()[1] 
    if gear then 
        gear.Parent = Player.Character 
        Player.Character.Torso["Right Shoulder"].CurrentAngle = math.rad(90)
    end
end

print(("[%s] Rendering ..."):format(jobId))
local result = game:GetService("ThumbnailGenerator"):Click(format, x, y, true)
print(("[%s] Done!"):format(jobId))

return result

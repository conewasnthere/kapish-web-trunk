pcall(function() game:GetService("InsertService"):SetFreeModelUrl("http://sets.pizzaboxer.xyz/Game/Tools/InsertAsset.ashx?type=fm&q=%s&pg=%d&rs=%d") end)
pcall(function() game:GetService("InsertService"):SetFreeDecalUrl("http://sets.pizzaboxer.xyz/Game/Tools/InsertAsset.ashx?type=fd&q=%s&pg=%d&rs=%d") end)

game:GetService("ScriptInformationProvider"):SetAssetUrl("http://tadah.rocks/asset/")
game:GetService("InsertService"):SetBaseSetsUrl("http://tadah.rocks/Game/Tools/InsertAsset.ashx?nsets=10&type=base")
game:GetService("InsertService"):SetUserSetsUrl("http://tadah.rocks/Game/Tools/InsertAsset.ashx?nsets=20&type=user&userid=%d&t=2")
game:GetService("InsertService"):SetCollectionUrl("http://tadah.rocks/Game/Tools/InsertAsset.ashx?sid=%d")
game:GetService("InsertService"):SetAssetUrl("http://tadah.rocks/asset/?id=%d")
game:GetService("InsertService"):SetAssetVersionUrl("http://tadah.rocks/Asset/?assetversionid=%d")
game:GetService("InsertService"):SetTrustLevel(0)

pcall(function() game:GetService("SocialService"):SetFriendUrl("http://tadah.rocks/Game/LuaWebService/HandleSocialRequest.ashx?method=IsFriendsWith&playerid=%d&userid=%d") end)
pcall(function() game:GetService("SocialService"):SetBestFriendUrl("http://tadah.rocks/Game/LuaWebService/HandleSocialRequest.ashx?method=IsBestFriendsWith&playerid=%d&userid=%d") end)
pcall(function() game:GetService("SocialService"):SetGroupUrl("http://tadah.rocks/Game/LuaWebService/HandleSocialRequest.ashx?method=IsInGroup&playerid=%d&groupid=%d") end)
pcall(function() game:GetService("SocialService"):SetGroupRankUrl("http://tadah.rocks/Game/LuaWebService/HandleSocialRequest.ashx?method=GetGroupRank&playerid=%d&groupid=%d") end)
pcall(function() game:GetService("SocialService"):SetGroupRoleUrl("http://tadah.rocks/Game/LuaWebService/HandleSocialRequest.ashx?method=GetGroupRole&playerid=%d&groupid=%d") end)

local ScriptContext = game:GetService("ScriptContext")
ScriptContext:AddStarterScript(83)

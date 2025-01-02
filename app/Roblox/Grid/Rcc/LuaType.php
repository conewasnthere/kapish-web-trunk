<?php

namespace App\Roblox\Grid\Rcc;

class LuaType
{
	public const LUA_TNIL = 'LUA_TNIL';
	public const LUA_TBOOLEAN = 'LUA_TBOOLEAN';
	public const LUA_TNUMBER = 'LUA_TNUMBER';
	public const LUA_TSTRING = 'LUA_TSTRING';
	public const LUA_TTABLE = 'LUA_TTABLE';

	public static function cast($value)
    {
		$conversions = [
            'NULL' 		=> LuaType::LUA_TNIL,
            'boolean'	=> LuaType::LUA_TBOOLEAN,
            'integer'	=> LuaType::LUA_TNUMBER,
            'double'	=> LuaType::LUA_TNUMBER,
            'string'	=> LuaType::LUA_TSTRING,
            'array'		=> LuaType::LUA_TTABLE,
            'object'	=> LuaType::LUA_TNIL
		];
		return $conversions[gettype($value)];
	}
}
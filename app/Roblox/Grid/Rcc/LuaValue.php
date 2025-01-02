<?php

namespace App\Roblox\Grid\Rcc;

class LuaValue
{
	public $value;
	public $type;
	public $table;

	public function __construct($baseValue)
    {
		if (isset($baseValue))
        {
			$luaValue = LuaValue::serializeValue($baseValue);
			foreach ($luaValue as $name => $child)
            {
				$this->$name = $child;
			}
		}
	}
	
    public static function serializeValue($php)
    {
		$lua = new LuaValue(null);
		$lua->type = LuaType::cast($php);

		if (is_array($php))
        {
			$lua->table = [];
			foreach ($php as $value)
            {
				array_push($lua->table, new LuaValue($value));
			}
		}
        else
        {
			$lua->value = $php;
		}

		return $lua;
	}

	public static function deserializeValue($lua)
    {
        $php;

		if (is_array($lua))
        {
			$php = [];
			foreach ($lua as $value)
            {
				array_push($php, LuaValue::deserializeValue($value));
			}
		}
        else
        {
			if ($lua->type == LuaType::LUA_TTABLE && isset($lua->table->LuaValue))
            {
				$php = [];
				if (is_array($lua->table->LuaValue))
                {
					$value = $lua->table->LuaValue;
				}
                else
                {
					$value = $lua->table;
				}

				foreach ($value as $value)
                {
					array_push($php, $value->deserialize());
				}
			}
            elseif ($lua->type == LuaType::LUA_TNIL)
            {
				$php = null;
			}
            else
            {
				// Direct read from LuaValue's value
				$php = $lua->value;
			}
		}

		return $php;
	}

	function deserialize()
    {
		return LuaValue::deserializeValue($this);
	}
}
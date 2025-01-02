<?php

namespace App\Roblox\Grid\Rcc;

class ScriptExecution
{
	public $name;
	public $script;
	public $arguments = [];

	function __construct($name, $script, $arguments = [])
    {
		$this->name = $name;
		$this->script = $script;

		foreach ($arguments as $argument)
        {
			array_push($this->arguments, new LuaValue($argument));
		}
	}
}
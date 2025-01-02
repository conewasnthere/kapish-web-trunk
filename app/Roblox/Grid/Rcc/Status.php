<?php

namespace App\Roblox\Grid\Rcc;

class Status
{
	public $version;
	public $environmentCount;

	public function __construct($version, $environmentCount)
    {
		$this->version = $version;
		$this->environmentCount = $environmentCount;
	}
}
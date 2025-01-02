<?php

namespace App\Enums;

// https://developer.roblox.com/en-us/api-reference/enum/Genre

enum AssetGenre : int
{
    case All = 0;
    case TownAndCity = 1;
    case Fantasy = 2;
    case SciFi = 3;
    case Ninja = 4;
    case Scary = 5;
    case Pirate = 6;
    case Adventure = 7;
    case Sports = 8;
    case Funny = 9;
    case WildWest = 10;
    case War = 11;
    case SkatePark = 12;
    case Tutorial = 13;

    /**
     * Gets the type's name
     *
     * @return string
     */
    public function fullname(): string
    {
        return match($this)
        {
            self::TownAndCity => 'Town and City',
            self::SciFi => 'Sci-Fi',
            self::WildWest => 'Wild West',
            self::SkatePark => 'Skate Park',
            default => $this->name
        };
    }
}

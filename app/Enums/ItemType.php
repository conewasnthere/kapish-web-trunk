<?php

namespace App\Enums;

enum ItemType : int
{
    case Image = 1;
    case TShirt = 2;
    case Audio = 3;
    case Mesh = 4;
    case Lua = 5;
    case HTML = 6;
    case Text = 7;
    case Hat = 8;
    case Place = 9;
    case Model = 10;
    case Shirt = 11;
    case Pants = 12;
    case Decal = 13;
    case Avatar = 16;
    case Head = 17;
    case Face = 18;
    case Gear = 19;
    case Badge = 21;
    case GroupEmblem = 22;
    case Animation = 24;
    case Arms = 25;
    case Legs = 26;
    case Torso = 27;
    case RightArm = 28;
    case LeftArm = 29;
    case LeftLeg = 30;
    case RightLeg = 31;
    case Package = 32;
    case YoutubeVideo = 33;
    case GamePass = 34;
    case App = 35;
    case Code = 37;
    case Plugin = 38;
    case SolidModel = 39;
    case MeshPart = 40;

    /**
     * Gets the asset type's full name
     *
     * @return string
     */
    public function fullname(): string
    {
        return match($this)
        {
            self::TShirt => 'T-Shirt',
            self::GroupEmblem => 'Group Emblem',
            self::RightArm => 'Right Arm',
            self::LeftArm => 'Left Arm',
            self::LeftLeg => 'Left Leg',
            self::RightLeg => 'Right Leg',
            self::GamePass => 'Game Pass',
            default => $this->name
        };
    }

    public static function sellableTypes(): array
    {
        return [
            self::TShirt,
            self::Audio,
            self::Hat,
            self::Model,
            self::Shirt,
            self::Pants,
            self::Decal,
            self::Face,
            self::Gear,
            self::Badge
        ];
    }

    public function isSellable(): bool
    {
        return in_array($this, self::sellableTypes());
    }

    public static function freeTypes(): array
    {
        return [
            self::Audio,
            self::Model,
            self::Decal,
        ];
    }

    public function isFree(): bool
    {
        return in_array($this, self::freeTypes());
    }

    /**
     * Gets the asset types that can be worn by a user.
     *
     * @return array
     */
    public static function wearableTypes(): array
    {
        return [
            self::Head,
            self::Face,
            self::Gear,
            self::Hat,
            self::TShirt,
            self::Shirt,
            self::Pants
        ];
    }

    /**
     * Checks if the asset type is a wearable type.
     *
     * @return bool
     */
    public function isWearable(): bool
    {
        return in_array($this, self::wearableTypes());
    }

    /**
     * Gets the asset types that are shown on the inventory sections.
     *
     * @return array
     */
    public static function inventoryTypes(): array
    {
        return [
            self::Head,
            self::Face,
            self::Gear,
            self::Hat,
            self::TShirt,
            self::Shirt,
            self::Pants,
            self::Decal,
            self::Model,
            self::Plugin,
            self::Animation,
            self::Place,
            self::GamePass,
            self::Audio,
            self::Badge,
            self::LeftArm,
            self::RightArm,
            self::LeftLeg,
            self::RightLeg,
            self::Torso,
            self::Package
        ];
    }

    /**
     * Checks if the asset type is shown on the inventory selection.
     *
     * @return bool
     */
    public function isInventoryType(): bool
    {
        return in_array($this, self::inventoryTypes());
    }

    /**
     * Gets the asset types that are shown on the favorites section on user profiles.
     *
     * @return array
     */
    public static function favoriteTypes(): array
    {
        return [
            self::Head,
            self::Face,
            self::Gear,
            self::Hat,
            self::TShirt,
            self::Shirt,
            self::Pants,
            self::Decal,
            self::Model
        ];
    }

    /**
     * Checks if the asset type is shown on the favorites selection.
     *
     * @return bool
     */
    public function isFavoriteType(): bool
    {
        return in_array($this, self::favoriteTypes());
    }

    /**
     * Gets the Font Awesome icon for this asset type.
     *
     * @return string
     */
    public function fontAwesomeIcon(): string
    {
        return match($this) {
            self::Place => 'fa-image-landscape',
            self::Model => 'fa-cubes',
            self::Decal => 'fa-file-image',
            self::Badge => 'fa-certificate',
            self::GamePass => 'fa-ticket',
            self::Audio => 'fa-volume-high',
            self::Animation => 'fa-person-running',
            self::Shirt => 'fa-shirt',
            self::TShirt => 'fa-shirt-tank-top',
            self::Pants => 'fa-clothes-hanger',
            self::Plugin => 'fa-puzzle-piece',
        };
    }

    /**
     * Gets the asset types that are shown on the develop page.
     * Note that not everything here is creatable through the develop page (places, models, etc).
     *
     * @return array
     */
    public static function developTypes(): array
    {
        return [
            self::Place,
            self::Model,
            self::Decal,
            self::Badge,
            self::GamePass,
            self::Audio,
            self::Animation,
            self::Shirt,
            self::TShirt,
            self::Pants,
            self::Plugin,
        ];
    }

    /**
     * Checks if the asset type is shown on the develop page.
     *
     * @return bool
     */
    public function isDevelopType(): bool
    {
        return in_array($this, self::developTypes());
    }

    /**
     * Checks if the asset type can be uploaded through the develop page
     *
     * @return bool
     */
    public function isDevelopCreatable(): bool
    {
        return match($this)
        {
            self::TShirt => true,
            self::Decal => true,
            self::Audio => true,
            self::Animation => true,
            default => false
        };
    }
}

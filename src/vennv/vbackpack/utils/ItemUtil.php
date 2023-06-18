<?php

/**
 * VBackpack - PocketMine plugin.
 * Copyright (C) 2023 - 2025 VennDev
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace vennv\vbackpack\utils;

use pocketmine\item\Item;
use pocketmine\item\StringToItemParser;
use pocketmine\nbt\tag\ByteArrayTag;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\IntArrayTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\LongTag;
use pocketmine\nbt\tag\ShortTag;
use pocketmine\nbt\tag\StringTag;

// Copy some functions from: https://github.com/Ree-jp-minecraft/StackStrage/tree/47fc4f15bc97da3a9248b997aba0c9c64f531855/src/ree_jp/stackstorage/api
// Thanks you so much!, Ree-jp.

final class ItemUtil {

    public static function getItem(string $item): Item {
        return StringToItemParser::getInstance()->parse($item);
    }

    public static function encodeItem(Item $item) : string {
        $itemToJson = self::itemToJson($item);
        return base64_encode(gzcompress($itemToJson));
    }

    public static function decodeItem(string $item) : Item {
        $itemFromJson = gzuncompress(base64_decode($item));
        return self::jsonToItem($itemFromJson);
    }

    public static function itemToJson(Item $item) : string {
        $cloneItem = (clone $item)->setCount(1);
        $itemNbt = $cloneItem->nbtSerialize();
        return json_encode(self::nbtToArray($itemNbt));
    }

    public static function jsonToItem(string $json) : Item {
        $itemNbt = self::jsonToNbt(json_decode($json, true));
        $tag = self::toCompoundTag($itemNbt);
        return Item::nbtDeserialize($tag);
    }

    public static function nbtToArray(CompoundTag|ListTag $tags) : array|false {
        $result = [];
        foreach ($tags as $key => $tag) {
            try {
                $result[$key] = match (true) {
                    $tag instanceof CompoundTag => ["nbt_tag" => NBTTag::Compound, "value" => self::nbtToArray($tag)],
                    $tag instanceof ListTag => ["nbt_tag" => NBTTag::List, "value" => self::nbtToArray($tag)],
                    $tag instanceof ByteArrayTag => ["nbt_tag" => NBTTag::ByteArray, "value" => $tag->getValue()],
                    $tag instanceof ByteTag => ["nbt_tag" => NBTTag::Byte, "value" => $tag->getValue()],
                    $tag instanceof DoubleTag => ["nbt_tag" => NBTTag::Double, "value" => $tag->getValue()],
                    $tag instanceof FloatTag => ["nbt_tag" => NBTTag::Float, "value" => $tag->getValue()],
                    $tag instanceof IntArrayTag => ["nbt_tag" => NBTTag::IntArray, "value" => $tag->getValue()],
                    $tag instanceof IntTag => ["nbt_tag" => NBTTag::Int, "value" => $tag->getValue()],
                    $tag instanceof LongTag => ["nbt_tag" => NBTTag::Long, "value" => $tag->getValue()],
                    $tag instanceof ShortTag => ["nbt_tag" => NBTTag::Short, "value" => $tag->getValue()],
                    $tag instanceof StringTag => ["nbt_tag" => NBTTag::String, "value" => $tag->getValue()],
                };
            } catch (\UnhandledMatchError $error) {
                return false;
            }
        }
        return $result;
    }

    public static function jsonToNbt(array $tags) : array|false {
        $result = [];
        foreach ($tags as $key => $array) {
            try {
                $tag = match ($array["nbt_tag"]) {
                    NBTTag::Compound->value => self::toCompoundTag(self::jsonToNbt($array["value"])),
                    NBTTag::List->value => new ListTag(self::jsonToNbt($array["value"])),
                    NBTTag::ByteArray->value => new ByteArrayTag($array["value"]),
                    NBTTag::Byte->value => new ByteTag($array["value"]),
                    NBTTag::Double->value => new DoubleTag($array["value"]),
                    NBTTag::Float->value => new FloatTag($array["value"]),
                    NBTTag::IntArray->value => new IntArrayTag($array["value"]),
                    NBTTag::Int->value => new IntTag($array["value"]),
                    NBTTag::Long->value => new LongTag($array["value"]),
                    NBTTag::Short->value => new ShortTag($array["value"]),
                    NBTTag::String->value => new StringTag($array["value"]),
                };
                $result[$key] = $tag;
            } catch (\UnhandledMatchError $error) {
                return false;
            }
        }
        return $result;
    }

    public static function toCompoundTag(array $array) : CompoundTag {
        $compound = new CompoundTag();
        foreach ($array as $key => $tag) {
            $compound->setTag($key, $tag);
        }
        return $compound;
    }

}

enum NBTTag : string {
    case Compound = "compound";
    case List = "list";
    case ByteArray = "byte_array";
    case Byte = "byte";
    case Double = "double";
    case Float = "float";
    case IntArray = "int_array";
    case Int = "int";
    case Long = "long";
    case Short = "short";
    case String = "string";
}
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
        $itemNBT = $cloneItem->nbtSerialize();
        return base64_encode(serialize($itemNBT);
    }

    public static function jsonToItem(string $json) : Item {
        $itemNBT = unserialize(base64_decode($json));
        return Item::nbtDeserialize($itemNBT);
    }

}

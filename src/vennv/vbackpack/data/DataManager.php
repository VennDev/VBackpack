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

namespace vennv\vbackpack\data;

use Exception;
use pocketmine\item\Item;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use vennv\vbackpack\utils\ItemUtil;
use vennv\vbackpack\utils\TypeBackpack;
use vennv\vbackpack\VBackpack;

final class DataManager {

    private static array $data = [];

    public static function setData(Player $player, $value) : void {
        self::$data[$player->getXuid()] = $value;
    }

    public static function getData(Player $player) : ?DataBackpack {
        return self::$data[$player->getXuid()] ?? null;
    }

    public static function removeData(Player $player) : void {

        $data = self::getData($player);
        if ($data !== null) {
            $item = $data->getItemInHand();
            try {
                $player->getInventory()->removeItem($item);
                $item->getNamedTag()->setString("items_backpack", $data->encodeItems());
                $player->selectHotbarSlot(0);
                $player->getInventory()->addItem($item);
            } catch (\Exception $e) {}
        }
        unset(self::$data[$player->getXuid()]);
    }

    public static function getConfig() : Config {
        return VBackpack::getInstance()->getConfig();
    }

    public static function getContentsBackpack(Player $player, Item $item) : array {
        $data = $item->getNamedTag()->getString("items_backpack");
        return (new DataBackpack($player))->decodeItems($data);
    }

    public static function getTypeBackpack(Item $item) : int {
        return $item->getNamedTag()->getInt("type_backpack");
    }

    public static function isBackpack(Item $item) : bool {
        try {
            return $item->getNamedTag()->getString("vbackpack") === "vbackpack";
        } catch (Exception $e) {
            return false;
        }
    }

    public static function getIdBackpack(Item $item) : string {
        if (!self::isBackpack($item)) return "";
        return $item->getNamedTag()->getString("id_backpack");
    }

    public static function getItemsBackpack(Item $item) : string {
        return $item->getNamedTag()->getString("items_backpack");
    }

    public static function generatorIdBackpack(Player $player) : string {
        return $player->getXuid() . "-" . microtime(true);
    }

    public static function giveBackpack(Player $player, int $type, int $amount) : void {

        $item = ItemUtil::getItem("chest");
        $name = "";
        switch ($type) {
            case TypeBackpack::TYPE_SMALL:
                $name = DataManager::getConfig()->getNested("name.small");
                break;
            case TypeBackpack::TYPE_MEDIUM:
                $name = DataManager::getConfig()->getNested("name.medium");
                break;
            case TypeBackpack::TYPE_LARGE:
                $name = DataManager::getConfig()->getNested("name.large");
                break;
        }
        $item->setCustomName($name);

        $item->getNamedTag()->setString("vbackpack", "vbackpack");
        $item->getNamedTag()->setString("id_backpack", self::generatorIdBackpack($player));
        $item->getNamedTag()->setInt("type_backpack", $type);
        $item->getNamedTag()->setString("items_backpack", (new DataBackpack($player))->encode());

        $player->getInventory()->addItem($item->setCount($amount));
    }

    public static function openBackpack(Player $player, Item $backpack) : void {

        $type = self::getTypeBackpack($backpack);
        $items = self::getContentsBackpack($player, $backpack);

        $data = new DataBackpack($player);
        $data->setWindowCurrent([
            "type" => $type,
            "items" => $items,
            "item_in_hand" => $backpack,
        ]);

        self::setData($player, $data);
        self::getData($player)->getBackpack();
    }

}
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

use vennv\muqsit\invmenu\InvMenu;
use vennv\muqsit\invmenu\transaction\InvMenuTransaction;
use vennv\muqsit\invmenu\transaction\InvMenuTransactionResult;
use vennv\muqsit\invmenu\type\InvMenuTypeIds;
use pocketmine\inventory\Inventory;
use pocketmine\item\Item;
use pocketmine\player\Player;
use vennv\vbackpack\utils\ItemUtil;
use vennv\vbackpack\utils\TypeBackpack;

final class DataBackpack {

    private Player $owner;
    private array $windowCurrent = [];

    public function __construct(Player $owner) {
        $this->owner = $owner;
    }

    public function getOwner() : Player {
        return $this->owner;
    }

    public function setWindowCurrent(array $window) : void {
        $this->windowCurrent = $window;
    }

    public function getWindowCurrent() : array {
        return $this->windowCurrent;
    }

    public function getTypeCurrent() : int {
        return $this->windowCurrent["type"];
    }

    public function getItemInHand() :  ? Item {
        return $this->windowCurrent["item_in_hand"] ?? null;
    }

    public function encode() : string {
        return base64_encode(gzcompress(json_encode($this->windowCurrent)));
    }

    public function decode(string $data) : array {
        return json_decode(gzuncompress(base64_decode($data)), true);
    }

    public function encodeItems() : string {
        return base64_encode(gzcompress(json_encode($this->windowCurrent["items"])));
    }

    public function decodeItems(string $items) : array {
        return json_decode(gzuncompress(base64_decode($items)), true);
    }

    /**
     * @throws \Throwable
     */
    public function saveItemsCurrent(array $contents) : void {
        $this->windowCurrent["items"] = [];
        foreach ($contents as $item) {
            $fiber = new \Fiber(function() use ($item) {
                $this->windowCurrent["items"][] = [$item->getCount(), ItemUtil::encodeItem($item)];
            });
            $fiber->start();
        }
    }

    public function getBackpack() : void {

        $type = null;
        $name = "";

        switch ($this->getTypeCurrent()) {
            case TypeBackpack::TYPE_SMALL:
                $type = InvMenuTypeIds::TYPE_HOPPER;
                $name = DataManager::getConfig()->getNested("name.small");
                break;
            case TypeBackpack::TYPE_MEDIUM:
                $type = InvMenuTypeIds::TYPE_CHEST;
                $name = DataManager::getConfig()->getNested("name.medium");
                break;
            case TypeBackpack::TYPE_LARGE:
                $type = InvMenuTypeIds::TYPE_DOUBLE_CHEST;
                $name = DataManager::getConfig()->getNested("name.large");
                break;
        }
        if ($type !== null) {

            $menu = InvMenu::create($type);
            $menu->setName($name);

            $inventory = $menu->getInventory();

            if (count($this->windowCurrent["items"]) > 0) {
                foreach ($this->windowCurrent["items"] as [$count, $item]) {
                    if (is_string($item)) {
                        $inventory->addItem(ItemUtil::decodeItem($item)->setCount($count));
                    }
                }
            }

            $menu->setListener(function(InvMenuTransaction $transaction) : InvMenuTransactionResult {
                $in = $transaction->getIn();

                if (DataManager::isBackpack($in)) {
                    return $transaction->discard();
                }

                return $transaction->continue();
            });

            $menu->setInventoryCloseListener(function(Player $player, Inventory $inventory) {
                $this->saveItemsCurrent($inventory->getContents());
                DataManager::removeData($this->owner);
            });

            $menu->send($this->owner);
        }
    }

}
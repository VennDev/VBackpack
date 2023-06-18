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

namespace vennv\vbackpack\listener;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use vennv\vbackpack\data\DataManager;

final class EventListener implements Listener {

    public function __construct() {}

    public function onPlayerInteract(PlayerInteractEvent $event) : void {
        $player = $event->getPlayer();
        $item = $player->getInventory()->getItemInHand();

        $isBackpack = DataManager::isBackpack($item);
        if ($isBackpack) {
            DataManager::openBackpack($player, $item);
            $event->cancel();
        }
    }

}
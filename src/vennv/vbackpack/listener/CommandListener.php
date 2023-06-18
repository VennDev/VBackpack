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

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use vennv\vbackpack\data\DataManager;
use vennv\vbackpack\utils\TypeBackpack;

final class CommandListener {

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool {
        if ($command->getName() == "vbackpack") {
            if (isset($args[0])) {
                if ($args[0] == "give") {
                    if (!isset($args[1])) {
                        return false;
                    } else {
                        if (!isset($args[2]) || !isset($args[3])) {
                            return false;
                        } else {
                            if (!is_numeric($args[3])) {
                                $sender->sendMessage("Amount must be a number");
                                return true;
                            }
                            $player = $sender->getServer()->getPlayerExact($args[1]);
                            if ($player == null) {
                                $sender->sendMessage("Player not found");
                                return true;
                            } else {
                                $type = $args[2];
                                switch ($type) {
                                    case "small":
                                        $type = TypeBackpack::TYPE_SMALL;
                                        break;
                                    case "medium":
                                        $type = TypeBackpack::TYPE_MEDIUM;
                                        break;
                                    case "large":
                                        $type = TypeBackpack::TYPE_LARGE;
                                        break;
                                    default:
                                        $sender->sendMessage("Types: small, medium, large");
                                        return false;
                                }
                                DataManager::giveBackpack($player, $type, (int) $args[3]);
                            }
                        }
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
        return true;
    }

}
<?php

declare(strict_types=1);

namespace vennv\muqsit\invmenu\type;

use vennv\muqsit\invmenu\InvMenu;
use vennv\muqsit\invmenu\type\graphic\InvMenuGraphic;
use pocketmine\inventory\Inventory;
use pocketmine\player\Player;

interface InvMenuType{

	public function createGraphic(InvMenu $menu, Player $player) : ?InvMenuGraphic;

	public function createInventory() : Inventory;
}
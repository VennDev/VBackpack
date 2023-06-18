<?php

declare(strict_types=1);

namespace vennv\muqsit\invmenu\type\graphic\network;

use vennv\muqsit\invmenu\session\InvMenuInfo;
use vennv\muqsit\invmenu\session\PlayerSession;
use pocketmine\network\mcpe\protocol\ContainerOpenPacket;

interface InvMenuGraphicNetworkTranslator{

	public function translate(PlayerSession $session, InvMenuInfo $current, ContainerOpenPacket $packet) : void;
}
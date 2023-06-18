<?php

declare(strict_types=1);

namespace vennv\muqsit\invmenu\session;

use vennv\muqsit\invmenu\InvMenu;
use vennv\muqsit\invmenu\type\graphic\InvMenuGraphic;

final class InvMenuInfo{

	public function __construct(
		readonly public InvMenu $menu,
		readonly public InvMenuGraphic $graphic,
		readonly public ?string $graphic_name
	){}
}
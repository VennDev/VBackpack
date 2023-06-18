<?php

declare(strict_types=1);

namespace vennv\muqsit\invmenu\type\util\builder;

use vennv\muqsit\invmenu\type\InvMenuType;

interface InvMenuTypeBuilder{

	public function build() : InvMenuType;
}
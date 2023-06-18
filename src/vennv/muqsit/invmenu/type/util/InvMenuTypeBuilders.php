<?php

declare(strict_types=1);

namespace vennv\muqsit\invmenu\type\util;

use vennv\muqsit\invmenu\type\util\builder\ActorFixedInvMenuTypeBuilder;
use vennv\muqsit\invmenu\type\util\builder\BlockActorFixedInvMenuTypeBuilder;
use vennv\muqsit\invmenu\type\util\builder\BlockFixedInvMenuTypeBuilder;
use vennv\muqsit\invmenu\type\util\builder\DoublePairableBlockActorFixedInvMenuTypeBuilder;

final class InvMenuTypeBuilders{

	public static function ACTOR_FIXED() : ActorFixedInvMenuTypeBuilder{
		return new ActorFixedInvMenuTypeBuilder();
	}

	public static function BLOCK_ACTOR_FIXED() : BlockActorFixedInvMenuTypeBuilder{
		return new BlockActorFixedInvMenuTypeBuilder();
	}

	public static function BLOCK_FIXED() : BlockFixedInvMenuTypeBuilder{
		return new BlockFixedInvMenuTypeBuilder();
	}

	public static function DOUBLE_PAIRABLE_BLOCK_ACTOR_FIXED() : DoublePairableBlockActorFixedInvMenuTypeBuilder{
		return new DoublePairableBlockActorFixedInvMenuTypeBuilder();
	}
}
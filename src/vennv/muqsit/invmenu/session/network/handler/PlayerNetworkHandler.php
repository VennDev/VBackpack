<?php

declare(strict_types=1);

namespace vennv\muqsit\invmenu\session\network\handler;

use Closure;
use vennv\muqsit\invmenu\session\network\NetworkStackLatencyEntry;

interface PlayerNetworkHandler{

	public function createNetworkStackLatencyEntry(Closure $then) : NetworkStackLatencyEntry;
}
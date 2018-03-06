<?php

namespace ElggMultisite {

    class PageHandler extends \Toro {

	static function hook($hookName, $callable) {
	    \ToroHook::add($hookName, $callable);
	}

    }

}


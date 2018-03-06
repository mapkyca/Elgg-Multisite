<?php

namespace ElggMultisite {
    
    class Site {
	
	private static $site;
	
	public function getWWWRoot() {
	    return '/'; // TODO: Do it better
	}
	
	
	/**
	 * 
	 * @return \ElggMultisite\Site
	 */
	public static function site() {
	    
	    if (empty(self::$site))
		self::$site = new Site();
	    
	    return self::$site;
	}
	
    }
}
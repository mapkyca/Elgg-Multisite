<?php

namespace ElggMultisite {

    class Site {

	private static $site;

	public function getDataDir() {
	    return dirname(dirname(dirname(dirname(__FILE__)))) . "/data/";
	}

	public function getWWWRoot() {
	    return '/'; // TODO: Do it better
	}

	public function checkInstall() {

	    if (!is_writable($this->getDataDir()))
		throw new \Exception ("It doesn't appear that your data directory (".$this->getDataDir ().") is writable.");
	    
	    try {
		if (!DB::db()) 
		    throw new \Exception("There was a problem connecting to the multisite directory.");
	    } catch (\Exception $ex) {
		throw new \Exception("There was a problem connecting to the multisite directory.");
	    }
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
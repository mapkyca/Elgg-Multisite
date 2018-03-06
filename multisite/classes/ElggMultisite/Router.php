<?php

namespace ElggMultisite {

    class Router {

	private $client;

	public function __construct() {

	}

	/**
	 * Load the appropriate setting based on the appropriate routing.
	 * @param type $url
	 * @return boolean
	 */
	public function route($url = "") {

	    if (empty($url))
		$url = $_SERVER['SERVER_NAME'];

	    if ($row = DB::execute("select * from domains where domain = :url limit 1", [':url' => $url])) {
		if ($obj = static::toObj($row)) {
		    if (!$obj->isSiteAccessible())
			return false;

		    return $obj;
		}
	    }

	    return new \stdClass();
	}

	/**
	 * Build an object out of a row.
	 * @param \ElggMultisite\stdClass $row
	 * @return boolean|\ElggMultisite\MultisiteDomain
	 * @throws \Exception
	 */
	public static function toObj($row) {
	    if (
		    (!($row instanceof stdClass)) ||
		    (!$row->id) ||
		    (!$row->class)
	    )
		throw new \Exception('Invalid handling class');

	    $class = $row->class;

	    if (class_exists($class)) {
		$object = new $class();

		if (!($object instanceof \ElggMultisite\Domain))
		    throw new \Exception('Class is invalid');

		$object->load($row->domain);

		return $object;
	    }

	    return false;
	}

    }

}
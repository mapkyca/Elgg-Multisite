<?php

namespace Elgg;

use Elgg\Cache\EntityCache;
use Elgg\Database\EntityTable;

/**
 * Preload entities based on properties of fetched objects
 *
 * @access private
 */
class EntityPreloader {

	/**
	 * @var callable DO NOT USE. For unit test mocking
	 * @access private
	 */
	public $_callable_cache_checker;

	/**
	 * @var callable DO NOT USE. For unit test mocking
	 * @access private
	 */
	public $_callable_entity_loader;

	/**
	 * Constructor
	 *
	 * @param EntityCache $entity_cache Entity cache
	 * @param EntityTable $entity_table Entity service
	 */
	public function __construct(EntityCache $entity_cache, EntityTable $entity_table) {
		$this->_callable_cache_checker = function ($guid) use ($entity_cache) {
			return $entity_cache->get($guid);
		};
		$this->_callable_entity_loader = function ($options) use ($entity_table) {
			return $entity_table->getEntities($options);
		};
	}

	/**
	 * Preload entities based on the given objects
	 *
	 * @param object[] $objects         Objects--e.g. loaded from an Elgg query--from which we can
	 *                                  pluck GUIDs to preload
	 * @param string[] $guid_properties e.g. array("owner_guid")
	 *
	 * @return void
	 */
	public function preload($objects, array $guid_properties) {
		$guids = $this->getGuidsToLoad($objects, $guid_properties);
		// If only 1 to load, not worth the overhead of elgg_get_entities(),
		// get_entity() will handle it later.
		if (count($guids) > 1) {
			call_user_func($this->_callable_entity_loader, array(
				'guids' => $guids,
			));
		}
	}

	/**
	 * Get GUIDs that need to be loaded
	 *
	 * To simplify the user API, this function accepts non-arrays and arrays containing non-objects
	 *
	 * @param object[] $objects         Objects from which to pluck GUIDs
	 * @param string[] $guid_properties e.g. array("owner_guid")
	 * @return int[]
	 */
	protected function getGuidsToLoad($objects, array $guid_properties) {
		if (!is_array($objects) || count($objects) < 2) {
			return array();
		}
		$preload_guids = array();
		foreach ($objects as $object) {
			if (is_object($object)) {
				foreach ($guid_properties as $property) {
					if (empty($object->{$property})) {
						continue;
					}
					$guid = $object->{$property};
					if ($guid && !call_user_func($this->_callable_cache_checker, $guid)) {
						$preload_guids[] = $guid;
					}
				}
			}
		}
		return array_unique($preload_guids);
	}
}

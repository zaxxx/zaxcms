<?php

namespace Zax\Traits;
use Zax,
	Nette;

/**
 * Trait TCacheable
 *
 * Some common cacheable behavior
 *
 * @package Zax\Traits
 */
trait TCacheable {

	/**
	 * @var Nette\Caching\Cache
	 */
	protected $cache;

	/**
	 * @var Nette\Caching\IStorage
	 */
	protected $cacheStorage;

	/**
	 * @param Nette\Caching\IStorage $storage
	 */
	public function injectCacheStorage(Nette\Caching\IStorage $storage) {
		$this->cacheStorage = $storage;
		$this->cache = new Nette\Caching\Cache($this->cacheStorage, get_class($this));
	}

	/**
	 * @param $namespace
	 * @return $this
	 */
	public function setCacheNamespace($namespace) {
		$this->cache = new Nette\Caching\Cache($this->cacheStorage, $namespace);
		return $this;
	}

}
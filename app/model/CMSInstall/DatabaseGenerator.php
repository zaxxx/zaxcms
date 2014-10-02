<?php

namespace ZaxCMS\Model\CMSInstall;
use Zax,
	ZaxCMS,
	Nette,
	Doctrine,
	Kdyby;

class DatabaseGenerator extends Nette\Object {

	protected $em;

	protected $cache;

	protected $schemaTool;

	protected $ns = 'ZaxCMS\Model\CMSInstall';

	protected $classes;

	protected $sessionSection;

	public function __construct(Kdyby\Doctrine\EntityManager $em,
	                            Zax\Utils\AppDir $appDir,
								Nette\Http\Session $session) {
		$this->em = $em;
		$this->schemaTool = new Doctrine\ORM\Tools\SchemaTool($this->em);
		$cacheStorage = new Nette\Caching\Storages\FileStorage($appDir . '/modules/Install/components/Install/installSchema');
		$this->cache = new Nette\Caching\Cache($cacheStorage);
		$this->sessionSection = $session->getSection('ZaxCMS.DatabaseGenerator');
	}

	public function cacheSchema() {
		$sqls = $this->getCreateSchemaSql();
		foreach($sqls as $key => $sql) {
			$this->cache->save($key, $sql);
		}
		$this->cache->save('sqlCount', count($sqls));
	}

	public function getCachedSqlCount() {
		if(!isset($this->sessionSection['sqlCount'])) {
			$this->sessionSection['sqlCount'] = $this->cache->load('sqlCount');
		}
		return $this->sessionSection['sqlCount'];
	}

	public function dropSchema() {
		$this->schemaTool->dropSchema($this->getEntityClasses());
	}

	public function runSqlFromCache($index) {
		$sql = $this->cache->load($index);
		if($sql === NULL) {
			return FALSE;
		}
		$this->em->getConnection()->executeQuery($sql);
	}

	public function getEntityClasses() {
		if($this->classes === NULL) {
			$this->classes = $this->em->getMetadataFactory()->getAllMetadata();
		}
		return $this->classes;
	}

	public function getCreateSchemaSql() {
		return $this->schemaTool->getCreateSchemaSql($this->getEntityClasses());
	}

	public function getSchemaTool() {
		return $this->schemaTool;
	}

}
<?php

namespace ZaxCMS\Model;
use Zax,
	ZaxCMS,
	Nette,
	Doctrine,
	Kdyby;

class DatabaseGenerator extends Nette\Object {

	protected $em;

	public function __construct(Kdyby\Doctrine\EntityManager $em) {
		$this->em = $em;
	}

	public function dropAndGenerate($skip = []) {
		$schemaTool = new Doctrine\ORM\Tools\SchemaTool($this->em);
		$classes = $this->em->getMetadataFactory()->getAllMetadata();
		foreach($classes as $id => $metadata) {
			if(in_array($metadata->name, $skip)) {
				unset($classes[$id]);
			}
		}
		$schemaTool->dropSchema($classes);
		$schemaTool->createSchema($classes);
	}

}
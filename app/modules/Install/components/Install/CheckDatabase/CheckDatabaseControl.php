<?php

namespace ZaxCMS\InstallModule\Components\Install;
use Nette,
    Zax,
    ZaxCMS\Model,
	Kdyby,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\Control;

class CheckDatabaseControl extends Control {

	protected $entityManager;

	protected $setDatabaseFactory;

	protected $testResult;

	public function __construct(Kdyby\Doctrine\EntityManager $entityManager, ISetDatabaseFactory $setDatabaseFactory) {
		$this->entityManager = $entityManager;
		$this->setDatabaseFactory = $setDatabaseFactory;
	}

	public function testConnection() {
		try {
			$this->entityManager->getConnection()->connect();
			$this->testResult = TRUE;
		} catch(\Exception $e) {
			$this->testResult = FALSE;
		}
	}

    public function viewDefault() {

    }

	public function viewResult() {
		if($this->testResult === NULL) {
			$this->testConnection();
		}
		$this->template->connected = $this->testResult;
	}
    
    public function beforeRender() {
        
    }

	protected function createComponentSetDatabase() {
	    return $this->setDatabaseFactory->create();
	}

}
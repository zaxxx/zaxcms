<?php

namespace Zax\Application;
use Zax,
	Nette;

class Application extends Nette\Application\Application {

	public $errorPresenters;

	/**
	 * Hook into how Nette handles error presenters
	 */
	public function run() {

		try {
			$this->onStartup($this);
			$this->processRequest($this->createInitialRequest());
			$this->onShutdown($this);

		} catch (\Exception $e) {
			$this->onError($this, $e);

			if ($this->catchExceptions && $this->errorPresenter) {

				if($this->errorPresenters !== NULL) {
					$trace = $e->getTrace();
					$this->errorPresenter = $this->errorPresenters['presenter'];
					foreach($trace as $key => $error) {
						if(isset($error['class']) && $error['class'] === 'Zax\Application\UI\SecuredControl') {
							$this->errorPresenter = $this->errorPresenters['control'];
							break;
						}
					}
				}

				if($this->errorPresenter) {

					try {
						$this->processException($e);
						$this->onShutdown($this, $e);

						return;
					} catch (\Exception $e){
						$this->onError($this, $e);
					}
				}
			}
			$this->onShutdown($this, $e);
			throw $e;
		}
	}

}
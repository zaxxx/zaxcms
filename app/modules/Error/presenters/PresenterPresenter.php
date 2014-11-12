<?php

namespace ZaxCMS\ErrorModule;
use Zax,
	Tracy\Debugger,
	Nette\Application,
	Nette;

class PresenterPresenter extends Nette\Object implements Nette\Application\IPresenter {

	protected $latteFactory;

	public function __construct(Nette\Bridges\ApplicationLatte\ILatteFactory $latteFactory) {
		$this->latteFactory = $latteFactory;
	}

	public function run(Application\Request $request)
	{
		$code = 500;
		$e = $request->parameters['exception'];
		if ($e instanceof Application\BadRequestException) {
			$code = $e->getCode();
		} else {
			Debugger::log($e, Debugger::ERROR);
		}

		$latte = $this->latteFactory->create();


		ob_start();
		$latte->render(__DIR__ . '/templates/presenter.latte', ['e' => $e, 'code' => $code]);
		return new Application\Responses\TextResponse(ob_get_clean());
	}

}
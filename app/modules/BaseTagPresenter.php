<?php

namespace ZaxCMS;
use Nette,
	ZaxCMS,
	ZaxCMS\Model,
	Nette\Application\UI\Presenter,
	Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
	Zax;

abstract class BaseTagPresenter extends BasePresenter {

	use Model\CMS\Service\TInjectTagService,
		ZaxCMS\Components\Article\TInjectArticleListFactory;

	protected $tag;

	public function actionDefault($slug) {
		$tag = $this->tagService->getBy(['slug' => $slug]);
		if($tag === NULL) {
			throw new Nette\Application\BadRequestException;
		}
		$tag->setTranslatableLocale($this->getLocale());
		$this->tagService->refresh($tag);
		$this->tag = $tag;
	}

	public function renderDefault($slug) {
		$this->template->tag = $this->tag;
	}

	protected function createComponentArticleList() {
		return $this->articleListFactory->create()
			->enablePaginator(5)
			->setTag($this->tag);
	}

}

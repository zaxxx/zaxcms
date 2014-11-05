<?php

namespace ZaxCMS;
use Nette,
	ZaxCMS,
	ZaxCMS\Model,
	Nette\Application\UI\Presenter,
	Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
	Zax;

abstract class BaseArticlePresenter extends BasePresenter {

	use ZaxCMS\DI\TInjectArticleConfig,
		ZaxCMS\Components\Article\TInjectArticleFactory,
		Model\CMS\Service\TInjectArticleService;

	protected $article;

	public function actionDefault($slug, $categorySlug) {
		$article = $this->articleService->getBy(['slug' => $slug]);
		if($article === NULL) {
			throw new Nette\Application\BadRequestException;
		}
		$article->setTranslatableLocale($this->getLocale());
		$this->articleService->refresh($article);
		$this->article = $article;
	}

	public function renderDefault($slug, $categorySlug) {
		$this->template->article = $this->article;
		$this->template->articleConfig = $this->articleConfig;
	}

	protected function createComponentArticle() {
		return $this->articleFactory->create()
			->setArticle($this->article);
	}

}

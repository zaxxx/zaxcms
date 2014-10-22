<?php

namespace ZaxCMS\FrontModule;
use Nette,
	ZaxCMS,
	ZaxCMS\Model,
	Nette\Application\UI\Presenter,
	Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
	Zax;

class ArticlePresenter extends BasePresenter {

	protected $articleService;

	protected $articleFactory;

	protected $article;

	public function __construct(Model\CMS\Service\ArticleService $categoryService,
	                            ZaxCMS\Components\Article\IArticleFactory $categoryFactory) {
		$this->articleService = $categoryService;
		$this->articleFactory = $categoryFactory;
	}

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
	}

	protected function createComponentArticle() {
		return $this->articleFactory->create()
			->setArticle($this->article);
	}

}

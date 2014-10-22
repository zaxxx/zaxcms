<?php

namespace ZaxCMS\FrontModule;
use Nette,
	ZaxCMS,
	ZaxCMS\Model,
	Nette\Application\UI\Presenter,
	Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
	Zax;

class TagPresenter extends BasePresenter {

	protected $tagService;

	protected $articleListFactory;

	protected $tag;

	public function __construct(Model\CMS\Service\TagService $tagService,
	                            ZaxCMS\Components\Article\IArticleListFactory $articleListFactory) {
		$this->tagService = $tagService;
		$this->articleListFactory = $articleListFactory;
	}

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

<?php

namespace ZaxCMS\FrontModule;
use Nette,
	ZaxCMS,
	ZaxCMS\Model,
	Nette\Application\UI\Presenter,
	Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
	Zax;

class CategoryPresenter extends BasePresenter {

	protected $categoryService;

	protected $articleListFactory;

	protected $category;

	public function __construct(Model\CMS\Service\CategoryService $categoryService,
								ZaxCMS\Components\Article\IArticleListFactory $articleListFactory) {
		$this->categoryService = $categoryService;
		$this->articleListFactory = $articleListFactory;
	}

	public function actionDefault($slug) {
		$category = $this->categoryService->getBy(['slug' => $slug]);
		if($category === NULL) {
			throw new Nette\Application\BadRequestException;
		}
		$category->setTranslatableLocale($this->getLocale());
		$this->categoryService->refresh($category);
		$this->category = $category;
	}

	public function renderDefault($slug) {
		$this->template->category = $this->category;
	}

	protected function createComponentArticleList() {
	    return $this->articleListFactory->create()
	        ->enablePaginator(5)
		    ->setCategory($this->category);
	}

}

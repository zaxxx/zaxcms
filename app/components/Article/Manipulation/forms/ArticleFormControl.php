<?php

namespace ZaxCMS\Components\Article;
use Nette,
    Zax,
    ZaxCMS\Model,
    Nette\Forms\Form,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\Control,
    Zax\Application\UI\FormControl;

abstract class ArticleFormControl extends FormControl {

	protected $articleService;

	protected $tagService;

	protected $article;

	public function injectArticleService(Model\CMS\Service\ArticleService $articleService) {
		$this->articleService = $articleService;
	}

	public function injectTagService(Model\CMS\Service\TagService $tagService) {
		$this->tagService = $tagService;
	}

	public function setArticle(Model\CMS\Entity\Article $article) {
		$this->article = $article;
		return $this;
	}

    public function viewDefault() {}
    
    public function beforeRender() {}

	abstract public function handleCancel();
    
    public function createForm() {
        $f = parent::createForm();

	    $f->addStatic('category', 'article.form.category')
		    ->addFilter(function($cat) {
			    return $cat->title;
		    });

	    $f->addText('title', 'article.form.title')
		    ->setRequired();

	    $tags = [];
	    if($this->article->tags !== NULL) {
		    foreach($this->article->tags as $tag) {
			    $tags[] = $tag->title;
		    }
	    }

	    $f->addArrayTextArea('tagsList', 'article.form.tags')
		    ->setDefaultValue($tags);

	    $f->addTexyArea('perex', 'article.form.perex')
		    ->getControlPrototype()->rows(5);

	    $f->addTexyArea('content', 'article.form.content')
		    ->getControlPrototype()->rows(15);

	    $f->addCheckbox('isPublic', 'article.form.publish');

	    $f->addButtonSubmit('saveArticle', 'common.button.save', 'ok');
	    $f->addLinkSubmit('cancel', '', 'remove', $this->link('cancel!'));

	    $f->enableBootstrap(['success' => ['saveArticle'], 'default' => ['cancel']], TRUE);

	    $this->binder->entityToForm($this->article, $f);

	    return $f;
    }

	protected function postProcess(Model\CMS\Entity\Article $article) {

	}
    
    public function formSuccess(Form $form, $values) {
        if($form->submitted === $form['saveArticle']) {
	        $this->binder->formToEntity($form, $this->article);

	        $tags = $values->tagsList;
	        $entityTags = [];
	        if($tags !== NULL && count($tags) > 0) {
		        foreach($tags as $tag) {
			        if(strlen($tag) > 0) {
				        $entityTags[] = $this->tagService->getOrCreateTag($tag);
			        }
		        }
	        }
	        $this->article->setTags($entityTags);

	        $this->postProcess($this->article);

	        $this->articleService->persist($this->article);
	        $this->articleService->flush();

	        $this->flashMessage('article.alert.articleSaved', 'success');
	        $this->presenter->redirect(':Front:Article:default', ['slug' => $this->article->slug, 'categorySlug' => $this->article->category->slug]);

        }
    }
    
    public function formError(Form $form) {
        
    }

}
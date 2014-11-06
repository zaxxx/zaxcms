<?php

namespace ZaxCMS\Components\Article;
use Doctrine\ORM\Query;
use Nette,
    Zax,
    ZaxCMS\Model,
    Nette\Forms\Form,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\Control,
    Zax\Application\UI\FormControl;

abstract class ArticleFormControl extends FormControl {

	use Model\CMS\Service\TInjectArticleService,
		Model\CMS\Service\TInjectTagService,
		Model\CMS\Service\TInjectAuthorService,
		Zax\Utils\TInjectRootDir;

	protected $article;

	public function setArticle(Model\CMS\Entity\Article $article) {
		$this->article = $article;
		return $this;
	}

    public function viewDefault() {}
    
    public function beforeRender() {
	    $this->template->htmlId = $this->lookupPath();
	    $this->template->article = $this->article;
    }

	abstract public function handleCancel();
    
    public function createForm() {
        $f = parent::createForm();

	    $main = $f->addContainer('main');

	    $main->addStatic('category', 'article.form.category')
		    ->addFilter(function($cat) {
			    return $cat->title;
		    });

	    $main->addText('title', 'article.form.title')
		    ->setRequired()
		    ->getControlPrototype()
		    ->addClass('input-lg');
	    $main['title']->getLabelPrototype()
		    ->addClass('lead');

	    $allAuthors = $this->authorService->findPairs(NULL, 'name');

	    $main->addAutoComplete('authorName', 'article.form.author', array_values($allAuthors))
		    ->setDefaultValue($this->article->author === NULL ? '' : $this->article->author->name);


	    $tags = [];
	    if($this->article->tags !== NULL) {
		    foreach($this->article->tags as $tag) {
			    $tags[] = $tag->title;
		    }
	    }

	    $allTags = $this->tagService->findPairs(NULL, 'title');

	    $main->addMultiAutoComplete('tagsList', 'article.form.tags', array_values($allTags))
		    ->setDefaultValue(implode(', ', $tags));

	    $main->addTexyArea('perex', 'article.form.perex')
		    ->getControlPrototype()->rows(5);

	    $main->addTexyArea('content', 'article.form.content')
		    ->getControlPrototype()->rows(15);

		$pic = $f->addContainer('pic');

	    if($this->article->image !== NULL) {
		    $pic->addCheckbox('deleteImg', 'common.form.removeImage');
	    }

	    $pic->addFileUpload('img', 'common.form.image')
		    ->addCondition($f::FILLED)
		    ->addRule($f::IMAGE);



	    $options = $f->addContainer('options');

	    $options->addCheckbox('isPublic', 'article.form.publish');

	    $options->addCheckbox('isVisibleInRootCategory', 'article.form.setVisibleOnMain');

	    $id = $this->lookupPath();
	    $options->addCheckbox('isMain', 'article.form.setAsMain')
		    ->setOption('id', $id . '-form-style-isMain');

	    $style = $f->addContainer('style');

	    $proto = Nette\Utils\Html::el('div');
	    $box = Nette\Utils\Html::el('div')->style('width:100px;height:50px;border:2px solid #000;float:left;margin-right:5px;');
		//$proto->add($box);
	    $proto->add('test');

		$styles = [
			0 => $proto,
			1 => $proto
		];

	    $style->addRadioList('displayStyleList', 'Zobrazení v seznamu', $styles);

	    $style->addRadioList('displayStyleRoot', 'Zobrazení v kořenové kategorii', $styles + [3 => 'stejné jako v seznamu'])
		    ->setOption('id', $id . '-form-style-displayStyleRoot');

	    $style->addRadioList('displayStyleMain', 'Pokud je označen jako hlavní', $styles + [3 => 'stejné jako v seznamu'])
		    ->setOption('id', $id . '-form-style-displayStyleMain');

	    $style->addRadioList('displayStyle', 'Zobrazení detailu', $styles);


	    $options['isVisibleInRootCategory']
		    ->addCondition(Form::EQUAL, TRUE)
		        ->toggle($id . '-form-style-displayStyleRoot')
		        ->toggle($id . '-form-style-displayStyleMain')
		        ->toggle($id . '-form-style-isMain');

	    $footer = $f->addContainer('footer');

	    $footer->addButtonSubmit('saveArticle', 'common.button.save', 'ok');
	    $footer->addButtonSubmit('saveArticleGo', 'article.button.saveAndGo', '');
	    $footer->addLinkSubmit('cancel', '', 'remove', $this->link('cancel!'));

	    $f->enableBootstrap(['success' => ['saveArticle', 'saveArticleGo'], 'default' => ['cancel']], TRUE);

	    $this->binder->entityToForm($this->article, $main);
	    $this->binder->entityToForm($this->article, $pic);
	    $this->binder->entityToForm($this->article, $options);
	    $this->binder->entityToForm($this->article, $style);

	    $f->autofocus('main-title');

	    return $f;
    }

	protected function postProcess(Model\CMS\Entity\Article $article) {

	}
    
    public function formSuccess(Form $form, $values) {
        if($form->submitted === $form['footer-saveArticle'] || $form->submitted === $form['footer-saveArticleGo']) {
	        $this->binder->formToEntity($form['main'], $this->article);
	        $this->binder->formToEntity($form['pic'], $this->article);
	        $this->binder->formToEntity($form['options'], $this->article);
	        $this->binder->formToEntity($form['style'], $this->article);

	        $tags = array_map('trim', explode(',', $values->main->tagsList));
	        $entityTags = [];
	        if($tags !== NULL && count($tags) > 0) {
		        foreach($tags as $tag) {
			        if(strlen($tag) > 0) {
				        $entityTags[$tag] = $this->tagService->getOrCreateTag($tag);
			        }
		        }
	        }
	        $this->article->setTags(count($entityTags) > 0 ? array_values($entityTags) : NULL);

	        $this->article->author = $this->authorService->getOrCreateAuthor($values->main->authorName);

	        $this->articleService->persist($this->article);
	        $this->articleService->flush();

	        if($values->pic->img instanceof Nette\Http\FileUpload && $values->pic->img->isOk()) {
		        $dir = 'upload' . DIRECTORY_SEPARATOR . 'articles' . DIRECTORY_SEPARATOR . $this->article->id;
		        if(!file_exists($this->rootDir . DIRECTORY_SEPARATOR . $dir)) {
			        Nette\Utils\FileSystem::createDir($this->rootDir . DIRECTORY_SEPARATOR . $dir);
		        }
		        $path = $dir . DIRECTORY_SEPARATOR . $values->pic->img->getSanitizedName();
		        $values->pic->img->move($this->rootDir . DIRECTORY_SEPARATOR . $path);

		        $this->article->image = $path;

		        $this->articleService->persist($this->article);
		        $this->articleService->flush();
	        } else if(isset($values->pic->deleteImg) && $values->pic->deleteImg) {
		        $this->article->image = NULL;

		        $this->articleService->persist($this->article);
		        $this->articleService->flush();
	        }

	        if($this->article->isMain) {
		        $qb = $this->articleService->getEntityManager()->createQueryBuilder();
		        $qb->update(Model\CMS\Entity\Article::getClassName(), 'a')
			        ->set('a.isMain', $qb->expr()->literal(FALSE))
			        ->where('a.id != :self')
			        ->setParameter('self', $this->article->id)
			        ->getQuery()
			        ->execute();
	        }

	        $this->flashMessage('article.alert.articleSaved', 'success');
	        if($form->submitted === $form['footer-saveArticleGo']) {
		        $this->presenter->redirect('Blog:article', ['article-slug' => $this->article->slug, 'article-view' => 'Default']);
	        } else {
		        $this->presenter->redirect('Blog:article', ['article-slug' => $this->article->slug, 'article-view' => 'Edit']);
	        }

        }
    }
    
    public function formError(Form $form) {
        
    }

}
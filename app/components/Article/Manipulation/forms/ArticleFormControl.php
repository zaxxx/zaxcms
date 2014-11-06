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

	    $id = $this->lookupPath('Nette\Application\UI\Presenter') . '-form';

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

	    $authors = [];
	    if($this->article->authors !== NULL) {
		    foreach($this->article->authors as $author) {
			    $authors[] = $author->name;
		    }
	    }
	    $allAuthors = $this->authorService->findPairs(NULL, 'name');
	    $main->addMultiAutoComplete('authorsList', 'article.form.author', array_values($allAuthors))
		    ->setDefaultValue(implode(', ', $authors));


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

	    // article has image and deleteImg checkbox is checked => hide the displayImg checkboxlist
	    if($this->article->image !== NULL) {
		    $pic->addCheckbox('deleteImg', 'common.form.removeImage')
			    ->addCondition($f::EQUAL, FALSE)
			        ->toggle($id . '-pic-displayImg');
	    }

	    $pic->addFileUpload('img', 'common.form.image')
		    ->setOption('id', $id . '-pic-image')
		    ->addCondition($f::FILLED)
			    ->addRule($f::IMAGE);

	    // upload is filled => show displayImg checkboxlist
	    $pic['img']
		    ->addCondition($f::FILLED)
		        ->toggle($id . '-pic-displayImg');

	    // article has image and deleteImg is checked => show upload
	    if($this->article->image !== NULL) {
		    $pic['deleteImg']
		        ->addCondition($f::EQUAL, TRUE)
			        ->toggle($id . '-pic-image');
	    }

	    $pic->addCheckboxList('displayImg', 'article.form.displayImg', [
		    'list' => 'V seznamech',
		    'root' => 'V kořenové kategorii',
		    'detail' => 'V detailu článku'
	    ])->setvalue($this->article->getDisplayImgArray())
		    ->setOption('id', $id . '-pic-displayImg');


	    $options = $f->addContainer('options');

	    $options->addCheckbox('isPublic', 'article.form.publish');

	    $options->addCheckbox('isVisibleInRootCategory', 'article.form.setVisibleOnMain');


	    $options->addCheckbox('isMain', 'article.form.setAsMain')
		    ->setOption('id', $id . '-style-isMain');

	    $style = $f->addContainer('style');

	    $proto = Nette\Utils\Html::el('div');
	    $box = Nette\Utils\Html::el('div')->style('width:100px;height:50px;border:2px solid #000;float:left;margin-right:5px;');
		//$proto->add($box);
	    $proto->add('test');

	    $style->addStatic('todo', '')
		    ->setValue('TODO!');

		// hide root-related stuff when category is not displayed in root
	    $options['isVisibleInRootCategory']
		    ->addCondition(Form::EQUAL, TRUE)
		        ->toggle($id . '-style-isMain');

	    $footer = $f->addContainer('footer');

	    $footer->addButtonSubmit('saveArticle', 'common.button.save', 'ok');
	    $footer->addButtonSubmit('saveArticleGo', 'article.button.saveAndGo', '');
	    $footer->addLinkSubmit('cancel', '', 'remove', $this->link('cancel!'));

	    $f->addProtection();

	    $f->enableBootstrap(['success' => ['saveArticle', 'saveArticleGo'], 'default' => ['cancel']], TRUE);

	    // fill the form from entity
	    $this->binder->entityToForm($this->article, $main);
	    $this->binder->entityToForm($this->article, $pic);
	    $this->binder->entityToForm($this->article, $options);
	    $this->binder->entityToForm($this->article, $style);

	    $f->autofocus('main-title');

	    return $f;
    }
    
    public function formSuccess(Form $form, $values) {
        if($form->submitted === $form['footer-saveArticle'] || $form->submitted === $form['footer-saveArticleGo']) {

	        // Fill entity
	        $this->binder->formToEntity($form['main'], $this->article);
	        $this->binder->formToEntity($form['pic'], $this->article);
	        $this->binder->formToEntity($form['options'], $this->article);
	        $this->binder->formToEntity($form['style'], $this->article);

	        $displayImg = array_flip($values->pic->displayImg);
	        $displayImg = array_fill_keys(array_keys($displayImg), TRUE);
	        $this->article->setDisplayImgFromArray($displayImg);

	        // Process tags
	        $authors = array_map('trim', explode(',', $values->main->tagsList));
	        $entityAuthors = [];
	        if($authors !== NULL && count($authors) > 0) {
		        foreach($authors as $author) {
			        if(strlen($author) > 0) {
				        $entityAuthors[$author] = $this->tagService->getOrCreateTag($author);
			        }
		        }
	        }
	        $this->article->setTags(count($entityAuthors) > 0 ? array_values($entityAuthors) : NULL);

	        // Process authors
	        $authors = array_map('trim', explode(',', $values->main->authorsList));
	        $entityAuthors = [];
	        if($authors !== NULL && count($authors) > 0) {
		        foreach($authors as $author) {
			        if(strlen($author) > 0) {
				        $entityAuthors[$author] = $this->authorService->getOrCreateAuthor($author);
			        }
		        }
	        }
	        $this->article->setAuthors(count($entityAuthors) > 0 ? array_values($entityAuthors) : NULL);

	        $this->articleService->persist($this->article);
	        $this->articleService->flush();

	        // Process delete image
	        if(isset($values->pic->deleteImg) && $values->pic->deleteImg) {
		        $file = $this->rootDir . DIRECTORY_SEPARATOR . $this->article->image;
		        if(file_exists($file))
			        Nette\Utils\FileSystem::delete($file);
		        $this->article->image = NULL;

		        $this->articleService->persist($this->article);
		        $this->articleService->flush();
	        }

	        // Process upload image
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
	        }

	        // Set as main
	        if($this->article->isMain) {
		        $qb = $this->articleService->getEntityManager()->createQueryBuilder();
		        $qb->update(Model\CMS\Entity\Article::getClassName(), 'a')
			        ->set('a.isMain', $qb->expr()->literal(FALSE))
			        ->where('a.id != :self')
			        ->setParameter('self', $this->article->id)
			        ->getQuery()
			        ->execute();
	        }

	        // Tadaaaa!!
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
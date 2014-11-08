<?php

namespace ZaxCMS\Components\Article;
use Doctrine\ORM\Query;
use Nette,
    Zax,
	Zax\Utils\Arrays,
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

	/** @var Model\CMS\Entity\Article */
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

	    $allAuthors = $this->authorService->findPairs(NULL, 'name');
	    $filledAuthors = Arrays::objectsToString($this->article->authors, function($author) {
		    return $author->name;
	    });
	    $main->addMultiAutoComplete('authorsList', 'article.form.authors', array_values($allAuthors))
		    ->setDefaultValue($filledAuthors);

	    $allTags = $this->tagService->findPairs(NULL, 'title');
	    $filledTags = Arrays::objectsToString($this->article->tags, function($tag) {
		    return $tag->title;
	    });
	    $main->addMultiAutoComplete('tagsList', 'article.form.tags', array_values($allTags))
		    ->setDefaultValue($filledTags);

	    $main->addTexyArea('perex', 'article.form.perex')
		    ->getControlPrototype()->rows(5);

	    $main->addTexyArea('content', 'article.form.content')
		    ->getControlPrototype()->rows(15);

		$pic = $f->addContainer('pic');

	    // article has image and deleteImg checkbox is checked => hide image options
	    if($this->article->image !== NULL) {
		    $pic->addCheckbox('deleteImg', 'common.form.removeImage')
			    ->addCondition($f::EQUAL, FALSE)
			        ->toggle($id . '-pic-customize');
	    }

	    $pic->addFileUpload('img', 'common.form.image')
		    ->setOption('id', $id . '-pic-image')
		    ->addCondition($f::FILLED)
			    ->addRule($f::IMAGE);

	    // upload is filled => show img options
	    $pic['img']
		    ->addCondition($f::FILLED)
		        ->toggle($id . '-pic-customize');

	    // article has image and deleteImg is checked => show upload
	    if($this->article->image !== NULL) {
		    $pic['deleteImg']
		        ->addCondition($f::EQUAL, TRUE)
			        ->toggle($id . '-pic-image');
	    }

	    $picConfig = $f->addContainer('picConfig');

	    $contexts = [
		    'list' => 'article.form.inLists',
		    'root' => 'article.form.inRoot',
		    'detail' => 'article.form.inArticleDetail'
	    ];

	    $picConfig->addCheckboxList('displayImg', 'article.form.displayImg', $contexts)
		    ->setValue(Arrays::boolToCbl($this->article->getImageConfig('visible')))
		    ->setOption('id', $id . '-pic-displayImg');

	    $picConfig->addCheckboxList('displayImgOpen', 'article.form.imgOpenOnClick', $contexts)
		    ->setValue(Arrays::boolToCbl($this->article->getImageConfig('open')))
		    ->setOption('id', $id . '-pic-displayImgOpen');

	    $opts = [
		    0 => 'article.form.floatLeft',
		    1 => 'article.form.wide'
	    ];
	    $configuredStyles = $this->article->getImageConfig('styles');

	    foreach($contexts as $context => $label) {
		    $picConfig->addRadioList('displayImgStyle' . ucfirst($context), $label, $opts)
			    ->setValue($configuredStyles[$context]);
	    }

	    $options = $f->addContainer('options');

	    $options->addCheckbox('isPublic', 'article.form.publish');

	    $options->addCheckbox('isVisibleInRootCategory', 'article.form.setVisibleOnMain');


	    $options->addCheckbox('isMain', 'article.form.setAsMain')
		    ->setOption('id', $id . '-style-isMain');

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

	    $f->autofocus('main-title');

	    return $f;
    }
    
    public function formSuccess(Form $form, $values) {
        if($form->submitted === $form['footer-saveArticle'] || $form->submitted === $form['footer-saveArticleGo']) {

	        // Fill entity
	        $this->binder->formToEntity($form['main'], $this->article);
	        $this->binder->formToEntity($form['pic'], $this->article);
	        $this->binder->formToEntity($form['options'], $this->article);

	        // Save image config
	        $conf = $this->article->imageConfig;
	        $formConf = $values->picConfig;
	        $this->article->setImageConfig([
		        'visible' =>  Arrays::cblToBool($conf['visible'], $formConf->displayImg),
		        'open' => Arrays::cblToBool($conf['open'], $formConf->displayImgOpen),
	            'styles' => [
			        'root' => $formConf->displayImgStyleRoot,
			        'list' => $formConf->displayImgStyleList,
			        'detail' => $formConf->displayImgStyleDetail
		        ]
	        ]);

	        // Process tags
	        $tags = Arrays::stringToObjects($values->main->tagsList, function($tag) {
		        return $this->tagService->getOrCreateTag($tag);
	        });
	        $this->article->setTags($tags);

	        // Authors
	        $authors = Arrays::stringToObjects($values->main->authorsList, function($author) {
		        return $this->authorService->getOrCreateAuthor($author);
	        });
	        $this->article->setAuthors($authors);

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
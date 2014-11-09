<?php

namespace ZaxCMS\Components\Blog;
use Nette,
    Zax,
    ZaxCMS\Model,
    Nette\Forms\Form,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\Control,
    Zax\Application\UI\FormControl;

class EditAuthorFormControl extends AbstractFormControl {

	use Model\CMS\Service\TInjectAuthorService;

	protected $author;

	public function setAuthor(Model\CMS\Entity\Author $author) {
		$this->author = $author;
		return $this;
	}

	public function handleCancel() {

	}

    public function viewDefault() {}

	public function beforeRender() {
		$this->template->htmlId = $this->lookupPath();
		$this->template->author = $this->author;
	}
    
    public function createForm() {
        $f = parent::createForm();

	    $id = $this->lookupPath('Nette\Application\UI\Presenter') . '-form';

	    $main = $f->addContainer('main');

	    $main->addText('name', 'common.form.name');

	    $main->addTexyArea('aboutAuthor', 'article.form.aboutAuthor')
		    ->getControlPrototype()
		    ->rows(10);

	    $pic = $f->addContainer('pic');
	    $this->createImageUpload($pic, $this->author->image);

	    $sidebar = $f->addContainer('sidebar');
	    $sidebar->addTexyArea('sidebarContent', 'common.form.sidebarContent')
		    ->getControlPrototype()
		    ->rows(10);

	    $footer = $f->addContainer('footer');

	    $footer->addButtonSubmit('saveAuthor', 'common.button.save', 'ok');
	    $footer->addLinkSubmit('cancel', '', 'remove', $this->link('cancel!'));

	    $f->addProtection();

	    $f->enableBootstrap(['success' => ['saveAuthor'], 'default' => ['cancel']], TRUE);

	    // fill the form from entity
	    $this->binder->entityToForm($this->author, $main);
	    $this->binder->entityToForm($this->author, $pic);
	    $this->binder->entityToForm($this->author, $sidebar);

	    $f->autofocus('main-name');

	    return $f;
    }
    
    public function formSuccess(Form $form, $values) {
	    if($form->submitted === $form['footer-saveAuthor']) {

		    // Fill entity
		    $this->binder->formToEntity($form['main'], $this->author);
		    $this->binder->formToEntity($form['pic'], $this->author);
		    $this->binder->formToEntity($form['sidebar'], $this->author);

		    $this->authorService->persist($this->author);
		    $this->authorService->flush();

		    if(isset($values->pic->deleteImg) && $values->pic->deleteImg) {
			    $this->deleteImage($this->author->image);
			    $this->author->image = NULL;

			    $this->authorService->persist($this->author);
			    $this->authorService->flush();
		    }

		    // Process upload image
		    if($values->pic->img instanceof Nette\Http\FileUpload && $values->pic->img->isOk()) {
			    $this->author->image = $this->processImageUpload($values->pic->img, 'author', $this->author->id);
			    $this->authorService->persist($this->author);
			    $this->authorService->flush();
		    }

		    $this->flashMessage('article.alert.articleSaved', 'success');
		    $this->presenter->redirect('Blog:author', ['author-slug' => $this->author->slug, 'author-view' => 'Default']);

	    }
    }
    
    public function formError(Form $form) {
        
    }

}
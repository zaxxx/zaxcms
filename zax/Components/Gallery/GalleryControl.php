<?php

namespace Zax\Components\Gallery;
use Nette,
    Zax,
    Zax\Application\UI\Control;

class GalleryControl extends Control {

    protected $galleryListFactory;

    public function __construct(IGalleryListFactory $galleryListFactory) {
        $this->galleryListFactory = $galleryListFactory;
    }

    public function viewDefault() {
        
    }
    
    public function beforeRender() {
        
    }

    protected function createComponentGalleryList() {
        return $this->galleryListFactory->create();
    }

}
<?php

namespace Zax\Components\Gallery;

interface IGalleryListFactory {

    /** @return GalleryListControl */
    public function create();

}
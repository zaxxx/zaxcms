<?php

namespace Zax\Components\Gallery;

interface IGalleryFactory {

    /** @return GalleryControl */
    public function create();

}
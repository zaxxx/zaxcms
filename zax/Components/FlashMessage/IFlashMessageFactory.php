<?php

namespace Zax\Components\FlashMessage;

interface IFlashMessageFactory {

    /** @return FlashMessageControl */
    public function create();

}
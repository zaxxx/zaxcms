<?php

namespace Zax\Application\UI;

interface IEmptyFactory {

    /** @return EmptyControl */
    public function create();

}
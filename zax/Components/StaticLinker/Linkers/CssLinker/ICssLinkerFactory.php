<?php

namespace Zax\Components\StaticLinker;

interface ICssLinkerFactory extends ILinkerFactory {

    /** @return CssLinkerControl */
    public function create();

}
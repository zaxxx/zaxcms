<?php

namespace Zax\Components\StaticLinker;

interface IStaticLinkerFactory {

    /** @return StaticLinkerControl */
    public function create();

}
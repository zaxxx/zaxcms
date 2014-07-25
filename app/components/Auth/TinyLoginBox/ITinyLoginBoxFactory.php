<?php

namespace ZaxCMS\Components\Auth;

interface ITinyLoginBoxFactory {

    /** @return TinyLoginBoxControl */
    public function create();

}
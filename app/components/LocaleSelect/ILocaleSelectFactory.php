<?php

namespace ZaxCMS\Components\LocaleSelect;

interface ILocaleSelectFactory {

    /** @return LocaleSelectControl */
    public function create();

}
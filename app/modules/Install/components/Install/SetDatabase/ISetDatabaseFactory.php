<?php

namespace ZaxCMS\InstallModule\Components\Install;

interface ISetDatabaseFactory {

    /** @return SetDatabaseControl */
    public function create();

}
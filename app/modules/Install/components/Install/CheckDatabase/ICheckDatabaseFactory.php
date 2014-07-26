<?php

namespace ZaxCMS\InstallModule\Components\Install;

interface ICheckDatabaseFactory {

    /** @return CheckDatabaseControl */
    public function create();

}
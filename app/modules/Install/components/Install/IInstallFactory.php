<?php

namespace ZaxCMS\InstallModule\Components\Install;

interface IInstallFactory {

    /** @return InstallControl */
    public function create();

}
<?php

namespace ZaxCMS\AdminModule\Components\Mails;

interface IMailsFactory {

    /** @return MailsControl */
    public function create();

}
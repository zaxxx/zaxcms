<?php

namespace Zax\Forms;
use Nette, Zax;

interface IBinder {

	public function formToEntity(Nette\Forms\Container $form, $entity);

	public function entityToForm($entity, Nette\Forms\Container $form);

}
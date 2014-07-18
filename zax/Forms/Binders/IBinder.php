<?php

namespace Zax\Forms;
use Nette, Zax;

interface IBinder {

	public function formToEntity(Nette\Forms\Form $form, $entity);

	public function entityToForm($entity, Nette\Forms\Form $form);

}
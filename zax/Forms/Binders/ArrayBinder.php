<?php

namespace Zax\Forms;
use Zax,
	Nette;

class ArrayBinder extends Nette\Object implements IBinder {

	public function formToEntity(Nette\Forms\Container $form, $entity) {
		foreach($form->getComponents() as $name => $control) {
			if(isset($entity[$name])) {
				$entity[$name] = $form[$name]->getValue();
			}
		}
		return $entity;
	}

	public function entityToForm($entity, Nette\Forms\Container $form) {
		foreach($form->getComponents() as $name => $control) {
			if(isset($entity[$name])) {
				$form[$name]->setValue($entity[$name]);
			}
		}
		return $form;
	}

}
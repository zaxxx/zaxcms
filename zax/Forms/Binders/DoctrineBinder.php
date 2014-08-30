<?php

namespace Zax\Forms;
use Zax,
	Nette;

class DoctrineBinder extends Nette\Object implements IBinder {

	public function formToEntity(Nette\Forms\Form $form, $entity) {
		foreach($form->getComponents() as $name => $control) {
			if(isset($entity->$name)) {
				$value = $form[$name]->getValue();
				if(strlen($value) === 0) {
					$value = NULL;
				}
				$entity->$name = $value;
			}
		}
		return $entity;
	}

	public function entityToForm($entity, Nette\Forms\Form $form) {
		foreach($form->getComponents() as $name => $control) {
			if(isset($entity->$name)) {
				$form[$name]->setValue($entity->$name);
			}
		}
		return $form;
	}

}
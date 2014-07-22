<?php

namespace ZaxCMS\Components\Menu;
use Nette,
	Zax;

class MenuList extends MenuAbstract {

	protected $items;

	public function getMenuItem($name) {
		return $this->getMenuItems()[$name];
	}

	public function getMenuItems() {
		if($this->items === NULL) {
			$items = [];
			if($this->data === NULL) {
				return $this->items = [];
			}
			if(is_object($this->data)) {
				foreach($this->menuRepository->getChildren($this->data) as $item) {
					$items[$item->name] = new MenuItem($item);
				}
			}else if(is_array($this->data)) {
				foreach($this->data as $key => $item) {
					if($key === 'class')
						continue;
					$items[$key] = new MenuItem($item);
				}
			}
			$this->items = $items;
		}
		return $this->items;
	}

}

<?php

namespace Zax\Components\Menu;
use Nette,
    Zax;


class MenuItem extends MenuAbstract {
    
    protected $submenu;
    
    public function getNhref() {
        return $this->getData('nhref');
    }

	public function getNhrefParams() {
		return $this->getData('nhrefParams');
	}

	public function getHref() {
		return $this->getData('href');
	}
    
    public function getText() {
        return $this->getData('text');
    }
    
    public function getHtml() {
        return $this->getData('html');
    }
    
    public function getStrict() {
        return $this->getData('strict', TRUE);
    }

	public function getHtmlTarget() {
		return $this->getData('htmlTarget');
	}
    
    public function hasSubmenu() {
        return $this->getData('submenu', -1) !== -1;
    }
    
    public function getSubmenu() {
        if(!$this->hasSubmenu())
            return NULL;
        else {
            if(!isset($this->submenu))
                $this->submenu = new MenuList($this->getData('submenu'));
            return $this->submenu;
        }
    }
    
    public function getMenuItem($name) {
        return $this->getMenuItems()[$name];
    }
    
}

<?php

namespace Zax\Components\Menu;

interface IMenuFactory {
    
    /** @return MenuControl */
    public function create();
    
}

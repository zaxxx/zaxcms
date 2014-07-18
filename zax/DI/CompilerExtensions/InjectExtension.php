<?php

namespace Zax\DI\CompilerExtensions;
use Nette,
    Nette\DI,
    Nette\DI\CompilerExtension;

/**
 * Enable @inject annotations and inject* methods, because FREEEEEDOOOOM!!!
 */
final class InjectExtension extends CompilerExtension {

	/**
	 *
	 */
	public function beforeCompile() {
        $builder = $this->getContainerBuilder();
        foreach ($builder->definitions as $definition) {
	        if(strpos($definition->getClass(), 'Kdyby') === FALSE)
                $definition->setInject(TRUE);
        }
    }
    
}
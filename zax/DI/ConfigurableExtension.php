<?php

namespace Zax\DI;
use Zax,
	Nette;

/**
 * @configClass ....
 */
abstract class ConfigurableExtension extends Nette\DI\CompilerExtension {

	protected function getDefaultConfig() {
		return Nette\Neon\Neon::decode(file_get_contents(dirname($this->reflection->fileName) . '/defaults.neon'));
	}

	protected function getConfigClass() {
		if($this->reflection->hasAnnotation('configClass')) {
			return $this->reflection->getAnnotation('configClass');
		}
		throw new Nette\InvalidStateException;
	}

	public function loadConfiguration() {
		$config = $this->getConfig($this->getDefaultConfig());

		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('config'))
			->setFactory($this->getConfigClass(), [$config]);

	}

}